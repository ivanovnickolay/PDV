<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09.05.2017
 * Time: 16:33
 */

namespace App\Utilits\loadDataExcel\configLoader;


use App\Utilits\loadDataExcel\createEntityForLoad\entityForLoad\createReestrOut;
use App\Utilits\loadDataExcel\createEntityForLoad\interfaceEntityForLoad\createEntityForLoad_interface;


/**
 * Реализация конфигурации для загрузки реестров полученных НН из файла
 * экспортированного из Медка РПН
 * Class configLoadReestrIn
 * @package AnalizPdvBundle\Utilits\loadDataFromExcel
 */
class configLoadReestrOut implements configLoader_interface
{

	/**
	 * Реализация сущности для загрузки
	 * @return createEntityForLoad_interface
	 */
	public function getEntityForLoad(): createEntityForLoad_interface
	{
		return new createReestrOut();
	}

	/**
	 * Реализация последнего столбца с данными который надо загружать
	 * @return string
	 */
	public function getLastColumn(): string
	{
		return 'DR';
	}

	/**
	 * Реализация количества строк которые считываются с файла за один раз
	 * @return int
	 */
	public function getMaxReadRow(): int
	{
		return 1000;
	}
}