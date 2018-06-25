<?php
 namespace App\Utilits\loadDataExcel\configLoader;

use App\Utilits\loadDataExcel\createEntityForLoad\interfaceEntityForLoad\createEntityForLoad_interface;

/**
 *  Описание методов для формирования конфигурации загрузки данных их файла
 * Interface configLoader_interface
 */
interface configLoader_interface
{
    /**
     * Реализация сущности для загрузки
     * @return createEntityForLoad_interface
     */
	public function getEntityForLoad():createEntityForLoad_interface;

	/**
	 * Реализация последнего столбца с данными который надо загружать
	 * @return string
	 */
	public function getLastColumn():string ;

	/**
	 * Реализация количества строк которые считываются с файла за один раз
	 * @return int
	 */
	public function getMaxReadRow():int;



}