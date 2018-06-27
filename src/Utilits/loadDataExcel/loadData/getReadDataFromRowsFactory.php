<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26.06.2018
 * Time: 11:30
 */

namespace App\Utilits\loadDataExcel\loadData;
use App\Utilits\loadDataExcel\configLoader\configLoader_interface;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use \App\Utilits\workToFileSystem\workWithFiles;


/**
 * Class getReadDataFromRowsFactory фабрика по созданию класса, который
 * будет читать строки из файла и обрабатывать их обработчиком
 * @package App\Utilits\loadDataExcel\loadData
 */
class getReadDataFromRowsFactory
{
    /**
     * Создает класс, который будет реализовывать алгоритм построчного чтения и обработки данных из файла
     * в зависимости от расширения файла
     * @param string $fileName
     * @param configLoader_interface $config
     * @return readDataFromRowsAbstract
     */
    public static function create(string $fileName,
                                  configLoader_interface $config){
        $extension = workWithFiles::getExtensionFileName($fileName);
        switch ($extension){
            case "xls":
                return new readDataFromRowsXls($fileName,$config);
                break;
                case "xlsx":
                    return new readDataFromRowsXlsx($fileName,$config);
                    break;
            default:
                throw new errorLoadDataException("Расширение файла не поддерживается!");

        }
    }
}