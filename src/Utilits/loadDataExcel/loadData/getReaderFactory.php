<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25.06.2018
 * Time: 21:07
 */

namespace App\Utilits\loadDataExcel\loadData;
use App\Utilits\loadDataExcel\configLoader\configLoader_interface;
use App\Utilits\loadDataExcel\createReaderFile\getReaderExcel;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;

/**
 * Class getReader фабрика создания класса ридера в зависимости от расширения файла
 * @package Utilits\loadDataExcel\loadData
 */
class getReaderFactory
{
    /**
     * @var configLoader_interface
     */
    private static $config;

    /**
     * Cоздание и первичная настройка объектов предназначенных для чтения данных из файлов
     * @param string $fileName название файла с данными
     * @param configLoader_interface|null $config конфигуратор с данными для загрузки
     * @return getReaderExcel|null|string
     * @throws errorLoadDataException
     */
    public static function createReader(string $fileName, configLoader_interface $config=null){
        $validFileType=array("xls","xlsx");
            $extensionFile= static::getExtensionFileName($fileName);
               if (!in_array($extensionFile,$validFileType)) {
                throw new errorLoadDataException("Расширение файла не поддерживается!");
                }
                    if (is_null($config)){
                            throw new errorLoadDataException("Класс конфигуратора отсутствует!");
                    }
        $reader = null;
               static::$config = $config;
        switch ($extensionFile){
            case "xls":
                $reader = static::getReaderXls($fileName);
                break;
            case "xlsx":
                $reader = static::getReaderXlsx($fileName);
                break;
        }
        return $reader;
    }

    private static function getExtensionFileName(string $fileName){
        $pathinfo = pathinfo($fileName);
            return $pathinfo['extension'];
    }

    /**
     * Создаем и делаем первичную настройку объект для чтения файлов с расширением Xls
     * @param string $fileName
     * @return getReaderExcel
     * @throws errorLoadDataException
     */
    private static function getReaderXls(string $fileName){
        $obj = new getReaderExcel($fileName, static::$config->getMaxReadRow());
            $obj->createFilter(static::$config->getLastColumn());
               return $obj;
    }

    /**
     * Создаем и делаем первичную настройку объект для чтения файлов с расширением Xls
     * @param string $fileName
     * @return getReaderExcel
     * @throws errorLoadDataException
     */
    private static function getReaderXlsx(string $fileName){
        $obj = ReaderFactory::create(Type::XLSX);
        $obj->open($fileName);
        return $obj;
    }
}