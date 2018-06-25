<?php
namespace App\Utilits\loadDataExcel\createReaderFile;
/**
 * Класс предназначен для организации по строчного чтения информации из файлов Эксель
 *  При создания класса ему передается:
 *      -   название файла для чтения информации. В @see getReaderExcel::isValidFileName() название файла проверяется на не пустое значение и на реальное существование
 *      -   максимальное значение строк которое читается за раз из файла. Используется в @see getReaderExcel::loadDataFromFileWithFilter()
 *        - используется для установки в фильтре на чтенеи данных
 *  Создание класса для чтения данных происходит в  @see getReaderExcel::createReader ()
 *      -   передача фабрике по созданию класса расширения файла определемого в  @see getReaderExcel::getFileType()
 *      -   установки фильтра на чтение данных, который настраивается в @see getReaderExcel::createFilter()
 *      -   установки отметки на чтение только данных - без форматирования.
 *  Чтение информации начинается вызовом @see getReaderExcel::loadDataFromFileWithFilter() с передачей ей начального номера строки,
 * данные с которой надо прочитать.
 *      - устанавливаются настройки фильтра для чтения с переданной в функцию строки и по максимальное количество строк
 *      - вызывается метод load ($fileName) @see BaseReader::load() который возвращает объект @see \PhpOffice\PhpSpreadsheet\Spreadsheet
 *        с прочитанным данными
 *      - из функции @see getReaderExcel::loadDataFromFileWithFilter() возвращается объект @see \PhpOffice\PhpSpreadsheet\Spreadsheet:
 *  Построчное чтение происходит при помощи @see getReaderExcel::getRowDataArray() в которую передается номер строки для чтения,
 * а возвращается двух мерный массив с данными - [0].[номер_столбца_с_данными]. Первое измерение всегда равно нулю -
 * это первая (нулевая) прочитанная строка с данными
 * --------------------------------------
 * эталонная реализация
 *
 * $obj = new getReaderExcel(fileName);
 * $obj->createFilter("DD");
 * $obj->createReader ();
 * $sheetReader = $obj->loadDataFromFileWithFilter(50);
 * %$arrayData =$obj->getRowDataArray(2);
 *
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.08.2016
 * Time: 0:20
  */

use App\Utilits\loadDataExcel\Exception\errorLoadDataException;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Класс возвращает Reader настроенный для чтения данных
 * Class getReaderExcel
 */
class getReaderExcel
{
	/**
	 * Количество строк, которые считываются за один раз из файла
	 * @var int
	 */
	private const filterChunkSize=1000;
	/**
	 * номер строки с которой надо начинать считывать файл
	 * @var int
	 */
	//private const filterStartRow=2;
	/**
	 * класс вида class Xlsx extends BaseReader
	 * @var BaseReader
	 */
	private $Reader;

	/**
	 * @var Spreadsheet
	 * загруженный файл Excel с параметрами указанными $ChunkFilter
	 */
	private $Excel;
	/**
	 * @var string наименование файла с путем к нему
	 */
	private $fileName;
	/**
	 * фильтр для чтения информации из файла
	 * @var \App\Utilits\loadDataExcel\createReaderFile\chunkReadFilter
	 */
	private $ChunkFilter;

	/**
	 * Название листа в книге
	 * @var string
	 */
	private $filterWorksheetName;
	/**
	 * @var int количестов строк которые читаются за раз
	 */
	private $maxReadRows;
    private $columnFirst;
    private $columnLast;

    /**
	 * Настройка Ридера для получения данных
	 * @param string $fileName Имя файла должно содержать полный путь к нему !!!
	 * @param int $maxReadRows количестов строк которые читаются за раз
     * @throws errorLoadDataException
	 */
	public function __construct(string $fileName, int $maxReadRows=0)
	{
		// Заполняем первоначальными значениями
		$this->fileName=$fileName;
		$this->columnFirst="A";
		// если ошибка проверки файла - бросаем исключение
		if (!$this->isValidFileName()){
            throw new errorLoadDataException("Файл для чтения данных не найден !");
        }
		// Если не указано сколько строк читать за раз устанавливаем константу
		if (0==$maxReadRows){
			$this->maxReadRows=self::filterChunkSize;
		} else{
			$this->maxReadRows=$maxReadRows;
		}
	}

	public function __destruct ()
	{
		unset($this->ChunkFilter);
		unset($this->Reader);
		unset($this->Excel);
	}

    /**
     * Проверяем наименование файла
     *  - оно не должно быть пустым
     *  - оно должно реально существовать
     *
     * @return bool
     */
	protected function isValidFileName():bool
	{
		if(!empty($this->fileName) or (file_exists($this->fileName)))
		{
            return true;
		}else{
		    return false;
        }
	}
	/**
	 * в версии PhpSpreadsheet достаточно просто передать расширение файла что-бы получить правильный Ридер
     *  по этому "вытягиваем" из файла его расширение и передаем его
     * @link https://phpspreadsheet.readthedocs.io/en/develop/topics/migration-from-PHPExcel/#manual-changes
     * Определение типа файла исходя из названия (расширения) файла
	 * @return string расширение файла
	 */
	protected function getFileType():string
	{
		$pathinfo = pathinfo($this->fileName);
		$extensionType = NULL;
		$extensionType = ucfirst($pathinfo['extension']);
		return $extensionType;
	}

	/**
	 * Установка параметров для фильтра по файлу
	 * @param string $columnLast конец диапазона столбцов текстовое по умолчанию $columnLast="Z"
	 * @param string $worksheetName наименовение рабочего листа книги по умолчанию $worksheetName=''
	 */
	public function createFilter(string $columnLast="Z",string $worksheetName='')
	{
			$this->filterWorksheetName=$worksheetName;
        /** @var chunkReadFilter $columnLast */
        $this->ChunkFilter=new chunkReadFilter($columnLast);
					$this->columnLast=$columnLast;
	}

	/**
	 * Создание экземпляра класса Ридера файла и настроек чтения
	 * включая фильтр
     * @deprecated
	 * @throws
	 */
	private function createReader ()
	{
		// если файл существует и класс фильтра подключен
		try {
					// получаем класс вида class PHPExcel_Reader_Excel2007 extends PHPExcel_Reader_Abstract implements PHPExcel_Reader_IReader
					$this->Reader = IOFactory::createReader ($this->getFileType());
						/* Подключаем фильтр **/
						$this->Reader->setReadFilter ($this->ChunkFilter);
							// Указываем что нам нужны только данные из файла - без форматирования
							$this->Reader->setReadDataOnly (true);
		} catch (Exception $e) {
					echo "Ошибка при создании Ридера ". $e->getMessage();
				}
	}

	/**
	 * получить класс ридера с настройками фильтрации данніх на чтение
     * с опцией получение данных из файла - без форматирования
	 * @return BaseReader
	 */
	public function getReader()
	{
		//$this->createReader();
        // если файл существует и класс фильтра подключен
        try {
            // получаем класс вида class PHPExcel_Reader_Excel2007 extends PHPExcel_Reader_Abstract implements PHPExcel_Reader_IReader
            $this->Reader = IOFactory::createReader ($this->getFileType());
            /* Подключаем фильтр **/
            $this->Reader->setReadFilter ($this->ChunkFilter);
            // Указываем что нам нужны только данные из файла - без форматирования
            $this->Reader->setReadDataOnly (true);

        } catch (Exception $e) {
            echo "Ошибка при создании Ридера ". $e->getMessage();
        }
        return $this->Reader;
	}
	/**
	 * получение объекта Excel с установленным значениями фильтров
	 * и получение прочитанных строк с данным начиная с $startRow и общим
	 * количеством установленным в $maxReadRows
	 * @param $startRow int первая строка для чтения данных
	 * @return Spreadsheet
	 */
	public function loadDataFromFileWithFilter(int $startRow)
	{
		$this->ChunkFilter->setRows($startRow,$this->maxReadRows);
		try{
			$this->Excel=$this->Reader->load($this->fileName);
		} catch (Exception $e){
			echo 'Ошибка чтения данных из файла' . $this->fileName . ': ' , $e->getMessage () , "\n";
		}
		return $this->Excel;
	}

    /**
     * чтение массива ячеек из указанной строки
     * @param $numRow int номер строки
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
	public function getRowDataArray(int $numRow):array
	{
		// вычисляем текущий диапазон ячеек с учетом номера строки $numRow
		$range="$this->columnFirst$numRow:$this->columnLast$numRow";
		// если не указано название листа
		if(empty($this->filterWorksheetName)){
			// читаем даные из текущего
            try {
                $d = $this->Excel->getActiveSheet()->rangeToArray($range, null, true, true, false);
                return $d;
            } catch (Exception $exception){

            }
		} else{
			// иначе ищем указаный лист в книге и читаем даные из него
			$f=$this->Excel->getSheetByName($this->filterWorksheetName)->rangeToArray($range,null,true,true,false);
			return $f;
		}
        return null;
	}

	/**
	 * Получаем количество строк с данными в открытом файле
	 * @return mixed
	 */
	public function getMaxRow()
	{
		$spreadsheetInfo=$this->Reader->listWorksheetInfo($this->fileName);
		$maxRows = $spreadsheetInfo[0]['totalRows'];
		return $maxRows;
	}

	public function unset_loadFileWithFilter()
	{
		unset($this->Excel);
	}

	/**
	 * Количество строк, которые считываются за один раз из файла
	 * @return int
	 */
	public function getFilterChunkSize(){
		return $this->maxReadRows;
	}
}