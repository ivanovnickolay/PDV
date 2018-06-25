<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09.05.2017
 * Time: 17:40
 */

namespace App\Utilits\loadDataExcel;


use App\Utilits\loadDataExcel\configLoader\configLoader_interface;
use Doctrine\ORM\EntityManager;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use App\Utilits\loadDataExcel\loadData\loadData;


/**
 * Класс проводит загрузку данных из файла
 * Class loadData
 * @package AnalizPdvBundle\Utilits\loadDataFromExcel
 */
class loadDataFromFile
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
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var configLoader_interface
	 */
	private $configLoad;

	/**
	 *  Подготовка данных для работы класса загрузки данных
	 *  Проверка файла на
	 * loadData constructor.
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param string $fileName
	 */
	public function __construct(EntityManager $em, string $fileName)
	{
		$this->em=$em;
		$this->fileName=$fileName;
		try {
			$this->validFileName();
		} catch (errorLoadDataException $e){
			echo $e->getMessage()." Название файла: ". $this->fileName;
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
     */
	public function loadDataFromFile(){
		try{
		// получаем конфигурацию по типу файла
			$this->getConfigLoad();
		} catch (errorLoadDataException $e){
			throw new errorLoadDataException("Ошибка при поиске конфигурации для загрузки данных. Не могу загрузить обработчик для файла : ");
		}
		// создаем загрузчик с нужной конфигурацией
		$loader=new loadData($this->em, $this->fileName, $this->configLoad);
		// загружаем данные из файла
		$loader->loadData();
		// очищаем не используемое
		unset($this->configLoad,$loader);
		// http://ru.php.net/manual/ru/features.gc.collecting-cycles.php
		gc_enable();
		gc_collect_cycles();
	}
}