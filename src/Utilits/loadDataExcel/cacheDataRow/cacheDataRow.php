<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 20.06.2018
 * Time: 15:53
 */

namespace App\Utilits\loadDataExcel\cacheDataRow;
use App\Entity\ReestrbranchIn;
use App\Entity\ReestrbranchOut;


/**
 * Класс предназначен для организации кеширования строк с данными.
 * Кеширование производится при чтении данных из файла во время валидации
 * Данные кеширования используются при сохранении данных в базу
 *
 *
 * Class cacheDataRow
 * @package App\Utilits\loadDataExcel\cacheDataRow
 */
class cacheDataRow
{
    private $arrayCache = array();

    private $arrayCorrectEntity = array(
        ReestrbranchIn::class,
        ReestrbranchOut::class
    );

    /**
     * сериализация и добавление объекта в кеш
     * дабавление возможно только для определенных классов
     * @param $obj
     * @throws \Exception если передан не объект или передан объект не того типа
     */
    public function addData($obj){
        if(!is_object($obj)){
            throw new \Exception("Для добавления в кеш передан не объект");
        }
        if (!in_array(
            get_class($obj),
            $this->arrayCorrectEntity)){
            throw new \Exception("Для добавления в кеш передан не верный объект");
        }

        $this->arrayCache[]=serialize($obj);
    }

    public function getArrayCache(){
        return $this->arrayCache;
    }

    public function unsetCache(){
        if (!is_null($this->arrayCache)){
            unset($this->arrayCache);
        }

    }

}