<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.02.2018
 * Time: 15:35
 */

namespace App\Utilits\loadDataExcel\configLoader;
use App\Utilits\loadDataExcel;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;


/**
 * Класс предназначен для централизации создания конфигурации загрузки
 * Class configLoaderFactory
 * @package App\Utilits\loadDataExcel\configLoader
 */
class configLoaderFactory
{

    /**
     * на основаниии анализа названия файла которое проводит parseNameFile::parseName($fileName)
     * формируется название класса котороый хранит конфигурацию загрузки данных из файла
     * на основании названия - создается соответствующий класс, который передается из функции
     * если возникает ошибка - вызывается исключение errorLoadDataException
     * test to loadRowsTest::class
     * @param $fileName
     * @return configLoader_interface
     * @throws errorLoadDataException
     * @see  loadDataExcel\loadData\loadRowsTest
     */
    public static function getConfigLoad($fileName){

        $regConfigLoader = array(
            "RestrIn"=>configLoadReestrIn::class,
            "RestrOut"=>configLoadReestrOut::class
        );
                // парсим название файла для получения типа сущности
            //$typeEntity =loadDataExcel\parseNameFile::parseName($fileName);
            $typeEntity =self::parseName($fileName);
            if ($typeEntity<>'') {
               // $className ='App\\Utilits\\loadDataExcel\\configLoader\\'.$templateNameConfigLoadClass . $typeEntity;
                if (key_exists($typeEntity,$regConfigLoader)){
                    $className=$regConfigLoader[$typeEntity];
                    if (class_exists($className)){
                        return new $className();
                    }else{
                        throw new errorLoadDataException("Ошибка создания объекта конфигурации для чтения информации из файла !");
                    }
                }
            }
            throw new errorLoadDataException("Для файла ".$fileName." не существует конфигурации для чтения информации из файла !");
    }

    /**
     * Функция парсит название файла и возвращает тип информации
     * которая в нем хранится. Информация используется для создания объекта
     * конфигуратора загрузки данных
     *
     * Парсер считывает последние 4 символа из названия файла и сравнивает полученное
     * значение с шаблоном из массива $regParseName при совпадении возвращает значение типа информации
     * если совпадений не найдено - возвращает пустую строку.
     *
     * @param string $fileName
     * @return string
     */
    public static function parseName(string $fileName):string {
        $regParseName = array(
            "TAB1"=>"RestrIn",
            "TAB2"=>"RestrOut",
        );

        $pathinfo = pathinfo($fileName);
            $baseNameFile=$pathinfo['filename'];
                $lenght = strlen($baseNameFile);
                    $key = substr(
                        $baseNameFile,
                        $lenght-4);
            if (key_exists($key,$regParseName)){
                return $regParseName[$key];
            }
        /**
        if ( 1 == substr_count($baseNameFile,'TAB1')){
            return "RestrIn";
        }
        if ( 1 == substr_count($baseNameFile,'TAB2')){
            return "RestrOut";
        }
         **/
        return "";

    }

}