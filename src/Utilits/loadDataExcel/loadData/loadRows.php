<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.09.2016
 * Time: 18:40
 */

namespace App\Utilits\loadDataExcel\loadData;
/*
 * класс предназначен для чтения строк из файла с данными
 * При создании объекта в него передаются
 *  -   класс обработчика строк с данными @see handlerRowAbstract
 *  -   путь и название к файлу, информацию с которого будем читать
 *  -   необходимую конфигурацию для настройки класса @see getReaderExcel
 * Эталонное использование
 *
 * для проверки файла
 *  $config = configLoaderFactory::getConfigLoad($fileName);
 *  $handler = new handlerRowsValid($em, $config);
 *  $load = new loadRows($fileName,$config);
 *  $load->readRows($handler);
 *  $arrayError = $handler->getResultHandlingAllRows();
 *
 * для чтения и сохранения информации
 *  $config = configLoaderFactory::getConfigLoad($fileName);
 *  $handler = new handlerRowsSave($em, $config);
 *  $load = new loadRows($fileName,$config);
 *
 *
 */

use App\Utilits\loadDataExcel\createEntityForLoad;
use App\Utilits\loadDataExcel\createEntityForLoad\interfaceEntityForLoad\createEntityForLoad_interface;
use App\Utilits\loadDataExcel\createReaderFile\getReaderExcel;
use App\Utilits\loadDataExcel\configLoader\configLoader_interface;
use App\Utilits\loadDataExcel\handlerRow\handlerRowAbstract;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;

/**
 * Класс служит для загрузки данных из файлов
 * Class loadData
 * @package AnalizPdvBundle\Utilits\loadData
 */
class loadRows
{
	/**
	 * @var getReaderExcel
	 */
	private $readerFile;

	/**
	 * @var createEntityForLoad_interface
	 */
	private $entity;
	/**
	 * @var int|null
	 */
	private $maxReadRow;


    /**
     * Настройка объекта
     * @param string $fileName
     * @param configLoader_interface $configLoad класс который содержит конфигурацию для загрузки
     * @throws errorLoadDataException вызывается при ошибках в данных конфигурации
     */
	public function __construct (string $fileName, configLoader_interface $configLoad)
	{
        $this->maxReadRow=$configLoad->getMaxReadRow();
	    // проверим есть ли в конфигурации значение последнего столбца с данными
		if (!empty($configLoad->getLastColumn())){
			$this->getReaderFile($fileName, $configLoad->getLastColumn());
		} else{
			throw new errorLoadDataException('Не указан последний столбец для чтения данных из файла');
		}


	}

	/**
	 * Получение и настройка Readerа
	 * @param string $fileName
	 * @param string $columnLast
	 */
	private function getReaderFile(string $fileName, string $columnLast): void
	{
           try{
               $this->readerFile = new getReaderExcel($fileName,$this->maxReadRow);
                   // настраиваем фильтр
                   $this->readerFile->createFilter($columnLast);
               // получаем Ридер
               $this->readerFile->getReader();
           } catch (errorLoadDataException $exception){

           }
	}


	public function __destruct ()
	{
		unset($this->readerFile);
		unset($this->validator);
		unset($this->entity);
	}


    /**
     * Чтение данных из файла кусками по количеству записей указаных в maxReadRow
     *  после чтения maxReadRow происходит запись сущностей  в базу в  $handlerRow->saveHandlingRows();
     *  обнуление прочитанных данных
     *  чтение новой порции данных
     * @param handlerRowAbstract $handler класс - обработчик прочитанных строк с данными
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
	public function readRows(handlerRowAbstract $handler)
	{
		$handlerRow  = $handler;
		    $maxRowToFile = $this->readerFile->getMaxRow ();
        for ($startRow = 2; $startRow <= $maxRowToFile; $startRow += $this->readerFile->getFilterChunkSize())
        {
            $this->readerFile->loadDataFromFileWithFilter ($startRow);
                $maxRowReader = $this->readerFile->getFilterChunkSize () + $startRow;
                    if ($maxRowReader > $maxRowToFile) {
                        // специально что бы была прочитана последняя строка с данными
                        $maxRowReader = $maxRowToFile + 1;
                    }
            for ($d = $startRow; $d < $maxRowReader; $d ++) {
                // Читаем строку из файла и возвращаем данные как массив
                $arrayRow = $this->readerFile->getRowDataArray ($d);
                //передаем в массив в обработчик
                $handler->handlerRow($arrayRow);
            }
            // выполняем операции после обработки части строк
            $handlerRow->saveHandlingRows();
               // очищаем загрузчик данных
               $this->readerFile->unset_loadFileWithFilter ();
               //http://ru.php.net/manual/ru/features.gc.collecting-cycles.php
               gc_enable();
                    gc_collect_cycles();
        }
	}

}