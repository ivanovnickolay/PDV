<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09.05.2017
 * Time: 17:40
 */

namespace App\Utilits\loadDataExcel;
/*
 * Класс - обертка которая позволяет централизировать в одном месте
 *  -   выбор класса с конфигурацией чтения данных getConfigLoad
 *  -   проверку наличия файла validFileName()
 *  -   получение обработчика строк setHandlerRows()
 *  -   правильную настройку объекта loadRows в loadRowsFromFile()
 *  -   загрузку строк  loadRows->readRows() с учетом обработчика handlerRows
 *
 * эталонное использование
 * $config = configLoaderFactory::getConfigLoad($fileName);
 * $load = new loadRowsFromFile($fileName);
 *      $handler = new handlerRowsValid($em, $config);
 *          $load->setHandlerRows($handler);
 *      $load->loadDataFromFile();
 * // если это обработка с целью проверки данных то
 *          $handler->getResultHandlingAllRows();
 */

use App\Utilits\loadDataExcel\configLoader\configLoader_interface;
use App\Utilits\loadDataExcel\configLoader\configLoaderFactory;
use App\Utilits\loadDataExcel\handlerRow\handlerRowAbstract;
use App\Utilits\loadDataExcel\loadData\loadRows;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;



/**
 * Класс проводит загрузку строк из файла и передачу их обработчику
 * для сохранения в базу
 * Class loadData
 * @package AnalizPdvBundle\Utilits\loadDataFromExcel
 * // todo Test !!!
 */
class loadRowsFromFile
{
	/**
	 * Шаблон название классов с конфигурациями загрузки
	 */
	const templateNameConfigLoadClass = "configLoad";

	/**
	 * @var string наименование файла данные из которого надо загрузить
	 */
	private $fileName;

	/**
	 * @var configLoader_interface
	 */
	private $configLoad;
    /**
     * @var handlerRowAbstract
     */
    private $handlerRows;

    /**
     * Подготовка данных для работы класса загрузки данных
     * Класс проводит загрузку строк из файла и передачу их обработчику для сохранения в базу
     *
     * loadData constructor.
     * @param string $fileName
     * @throws errorLoadDataException
     */
	public function __construct(string $fileName)
	{
		//$this->em=$em;
		$this->fileName=$fileName;
		try {
			$this->validFileName();
		} catch (errorLoadDataException $e){
			//echo $e->getMessage()." Название файла: ". $this->fileName;
            throw new errorLoadDataException("Файл для чтения данных не найден !");
		}

	}

	/**
	* Проверяем наименование файла
	* @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException если не найден файл для чтения
	*/
	private function validFileName()
	{
		if(empty($this->fileName) or (!file_exists($this->fileName)))
		{
            throw new errorLoadDataException("Файл для чтения данных не найден !");
        }
	}

	/**
	 * Загрузка конфигурации для Ридера
     *
     * @deprecated
	 * @throws errorLoadDataException если не найдена конфигурация загрузчика даных
	 */
	private function getConfigLoad(){
	    // парсим название файла для получения типа сущности
		$typeEntity =parseNameFile::parseName($this->fileName);
			if ($typeEntity<>'') {
				$className = self::templateNameConfigLoadClass . $typeEntity;
				$this->configLoad = new $className();
			} else{
				throw new errorLoadDataException();
			}
			//unset($obj);
	}

    /**
     * @param handlerRowAbstract $handlerRow
     */
    public function setHandlerRows(handlerRowAbstract $handlerRow){
	    $this->handlerRows = $handlerRow;
    }

    /**
     * Загрузка данных из файла
     *  - получение конфигуратора (configLoader_interface) по имени файла
     *  - создание класса загрузчика (loadData) с передачей ему
     *      - ссылки на EntityManager
     *      - название файла данные из которого надо загрузить
     *      - полученный объект конфигуратора
     *  - запуск загрузки данных
     *  - обнуление
     *      - класса конфигуратора
     *      - класса загрузчика
     *  - запуск сборщика мусора
     * @throws errorLoadDataException  Ошибка при поиске конфигурации для загрузки. Не известный файл
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
	public function loadDataFromFile(){
		try{
		// получаем конфигурацию по типу файла
			//$this->getConfigLoad();
            $this->configLoad = configLoaderFactory::getConfigLoad($this->fileName);
		} catch (errorLoadDataException $e){
			throw new errorLoadDataException("Ошибка при поиске конфигурации для загрузки данных. Не могу загрузить обработчик для файла : ");
		}
		if (empty($this->handlerRows)){
            throw new errorLoadDataException("Ошибка при поиске обработчика строк с данными . Не загружен обрабочик строк  ");
        }
		// создаем загрузчик с нужной конфигурацией
		$loader=new loadRows($this->fileName, $this->configLoad);
            // загружаем данные из файла
            $loader->readRows($this->handlerRows);
		// очищаем не используемое
		unset($this->configLoad,$loader);
		// http://ru.php.net/manual/ru/features.gc.collecting-cycles.php
		gc_enable();
		gc_collect_cycles();
	}
}