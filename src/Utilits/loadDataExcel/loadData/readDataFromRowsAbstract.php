<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25.06.2018
 * Time: 21:02
 */

namespace App\Utilits\loadDataExcel\loadData;


use App\Utilits\loadDataExcel\configLoader\configLoader_interface;
use App\Utilits\loadDataExcel\createReaderFile\getReaderExcel;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use App\Utilits\loadDataExcel\handlerRow\handlerRowAbstract;
use App\Utilits\loadDataExcel\loadData\getReaderFactory;
use Box\Spout\Reader\XLSX\Reader;


/**
 * абстрактный класс как замена class loadRows
 * предназначен для организации чтения строк с данными из разных файлов
 * вызывается в loadRowsFromFile::loadDataFromFile после всех проверок
 * Class readDataFromRowsAbstract
 * @package Utilits\loadDataExcel\loadData
 */
abstract class readDataFromRowsAbstract
{
    /**
     * @var configLoader_interface
     */
    protected $configLoader;

    /**
     * @var Reader::class|getReaderExcel
     */
    protected $readerFile;
    /**
     * Настройка объекта
     * @param string $fileName
     * @param configLoader_interface $configLoad класс который содержит конфигурацию для загрузки
     * @throws errorLoadDataException вызывается при ошибках в данных конфигурации
     */
    public function __construct(string $fileName, configLoader_interface $configLoad)
    {
        $this->configLoader = $configLoad;
        // проверим есть ли в конфигурации значение последнего столбца с данными
        if (!empty($this->configLoader->getLastColumn())) {
            $this->getReaderFile($fileName);
        } else {
            throw new errorLoadDataException('Не указан последний столбец для чтения данных из файла');
        }
    }


    /**
     * Получение и настройка Readerа
     * @param string $fileName
     */
    private function getReaderFile(string $fileName){
        try{
          $this->readerFile=getReaderFactory::createReader($fileName,$this->configLoader);
        } catch (errorLoadDataException $exception){

        }
    }


    public function __destruct ()
    {
        unset($this->readerFile);
        unset($this->validator);
        unset($this->entity);
    }

    abstract public function readRows(handlerRowAbstract $handler);
}