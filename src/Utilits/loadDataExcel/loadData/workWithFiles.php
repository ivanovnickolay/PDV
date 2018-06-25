<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04.09.2016
 * Time: 16:19
 */

namespace App\Utilits\loadDataExcel\loadData;


/**
 * Назначение класса - работа с файловой системой
 * Class LoadDataFromDir
 * @package AnalizPdvBundle\Utilits\loadData
 * @deprecated
 */
class workWithFiles
{
	/**
	 * указание пути к директории файлы из которой надо загрузить
	 * @var string
	 */
	private $nameDir;
	/**
	 * массив [название файла с путем к нему]=>[обрабочик файла]
	 * @var array
	 */
	private $fileToDir;

	/**
	 * Конструктор класса предназначенного для работы с файловой системой
	 *
	 * @param string $nameDir название папки
	 */
	public function __construct (string $nameDir)
	{
		$this->nameDir=$nameDir;
		$this->fileToDir=array();
		return $this;
	}

	/**
	 * проверка имени файла на соответствие правилам
	 * - файл Excel
	 * - наименование файла содержит или tab1 & tab2
	 * @link  http://ru.stackoverflow.com/questions/14116/%D0%9E%D0%BF%D1%80%D0%B5%D0%B4%D0%B5%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5-%D0%BD%D0%B0%D0%BB%D0%B8%D1%87%D0%B8%D1%8F-%D1%81%D0%B8%D0%BC%D0%B2%D0%BE%D0%BB%D0%BE%D0%B2-%D0%B2-%D1%81%D1%82%D1%80%D0%BE%D0%BA%D0%B5
	 * @param $fileName
	 * @return bool
	 */
	public function isValidFile($fileName)
	{
		//допустимые типы файлов
		$validFileType=array("xls","xlsx");
		$pathinfo = pathinfo($fileName);
		//$baseNameFile=$pathinfo['filename'];
		// если расширение файла не поддерживатеся возвращаем ложь
		if (!in_array($pathinfo['extension'],$validFileType)) {
			return false;
		} else {
			return true;
		}
	}


    /**
     * по названию файла определяем тип обработчика файла
     * @param string $fileName
     * @return string
     */
	public function getTypeHandlerFile(string $fileName)
	{
		$pathinfo = pathinfo($fileName);
		$baseNameFile=$pathinfo['filename'];
		if ( 1 == substr_count($baseNameFile,'TAB1')){
			return "RestrIn";
		}
		if ( 1 == substr_count($baseNameFile,'TAB2')){
			return "RestrOut";
		}
		return "";
	}

	/**
	 * возвращаем массив файлов Эксель (!) как массив [название файла с путем к нему]
	 * из директории которые прошли проверку
	 * @return array
	 */
	public function getFilesFromDir()
	{
		$dir = new \DirectoryIterator($this->nameDir);
		foreach ($dir as $fileinfo) {
			if (!$fileinfo->isDot()) {
				// проверяем нужного ли типа документ
				if($this->isValidFile($fileinfo->getFilename())){
					// если это файл Экселя то добавляем в массив
					$this->fileToDir[]=$fileinfo->getPathname();
				}
			}
		}
		return $this->fileToDir;
	}



	/**
	 * переносит файл между диекториями
	 * @param $fromFile string откуда скопировать файл с указанием пути к истокнику вплоть до файла
	 * @param $toFile string директория куда копировать файл
	 */

	public static function moveFiles($fromFile, $toFile)
	{
		// если пути не пустые и  реально существует
		if ((!empty($fromFile)) and (!empty($toFile)) and (file_exists($fromFile)) and (is_dir($toFile)))
		{
			$pathinf = pathinfo($fromFile);
			$f=$pathinf['basename'];
			$toFileNew=$toFile."/".$f;
				copy($fromFile,$toFileNew);
				unlink($fromFile);
		}
	}
}