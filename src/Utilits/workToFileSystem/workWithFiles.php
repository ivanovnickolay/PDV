<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04.09.2016
 * Time: 16:19
 */

namespace App\Utilits\workToFileSystem;

use App\Utilits\loadDataExcel\configLoader\configLoaderFactory;


/**
 * Назначение класса - работа с файловой системой
 * Class workWithFiles
 */
class workWithFiles
{
	/**
	 * проверка имени файла на соответствие правилам
	 * - файл Excel
	 * - наименование файла позволяет определить тип конфигуратора загрузки
	 * @link  http://ru.stackoverflow.com/questions/14116/%D0%9E%D0%BF%D1%80%D0%B5%D0%B4%D0%B5%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5-%D0%BD%D0%B0%D0%BB%D0%B8%D1%87%D0%B8%D1%8F-%D1%81%D0%B8%D0%BC%D0%B2%D0%BE%D0%BB%D0%BE%D0%B2-%D0%B2-%D1%81%D1%82%D1%80%D0%BE%D0%BA%D0%B5
	 * @param $fileName
	 * @return bool
	 */
	private static function isValidFile($fileName):bool
	{
        $validFileType=array("xls","xlsx");
        $pathinfo = pathinfo($fileName);
        $baseNameFile=$pathinfo['filename'];
        // если расширение файла не поддерживатеся возвращаем ложь
        if (!in_array($pathinfo['extension'],$validFileType)) {
            return false;
        }
	    //проверим есть ли конфигуратор для Этого файл
	    if(empty(configLoaderFactory::parseName($baseNameFile))){
	        return false;
        }else{
	        return true;
        }

	}

    /**
     * возвращаем массив файлов которые прошли проверку (!) как массив [название файла с путем к нему]
     * из директории
     * @param $nameDir string наименование директории, файлы из которой надо обработать
     * @return array
     * @throws \Exception если не существует директории для чтения информации
     */
    public static function getArrayFilesFromDir($nameDir):array
    {
        $fileToDir=array();
        if(!is_dir($nameDir)){
            throw new \Exception('Директория для чтения файлов не найдена! ');
        }
        $dir = new \DirectoryIterator($nameDir);
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                // есди файл верного типа и имеет конфигуратор для чтения
                if(self::isValidFile($fileinfo->getFilename())){
                    $fileToDir[]=$fileinfo->getPathname();
                }
            }
        }
        return $fileToDir;
    }


    /**
	 * переносит файл между директориями
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

    /**
     * Создает файл с описанием ошибок
     *
     * @param string $dirSaveFile директория для сохранения файла
     * @param string $nameFile название файла с ошибками. Рекомендуется
     * использовать название проверяемого файла без расщирения
     * @param array $arrayError массив данных, которые генерирует handlerRowsValid::getResultHandlingAllRows
     * @throws \Exception Директория для сохранения файла не найдена
     */
	public static function createFileErrorValidation(string $dirSaveFile,string $nameFile, array $arrayError){
	    if(!is_dir($dirSaveFile) or(!is_writable($dirSaveFile))){
	        throw new \Exception("Директория для сохранения файла не найдена");
        }
        $pathinfo = pathinfo($nameFile);
        $baseNameFile=$pathinfo['filename'];
        $fileNameWithDir = $dirSaveFile."/".$baseNameFile.'.log';
	    foreach ($arrayError as $key=>$value){
	        if(!empty($value)) {
                $stringForSave = "Строка № $key содержит ошибки =>> $value\n";
                file_put_contents($fileNameWithDir, $stringForSave, FILE_APPEND);
            }
        }


    }
}