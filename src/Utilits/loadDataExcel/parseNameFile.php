<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09.05.2017
 * Time: 17:46
 */

namespace App\Utilits\loadDataExcel;


/**
 * фтализирует название файла и возвращает тип сущности которая будет загружаться
 * Class parseNameFile
 * @package AnalizPDVBundle\Utilits\loadDataFromExcel
 * @deprecated configLoaderFactory::class реализовано в
 */
class parseNameFile
{
	/**
	 * parseNameFile constructor.
	 */
	public function __construct()
	{
		return $this;
	}

	/**
	 * по названиею файла определяет в какую именно сущность надо загружать данные из файла
	 * если файл содержит
	 * -  TAB1 то возвращает RestrIn
	 * -  TAB2 то возвращает RestrOut
	 *
	 * если ни чего не содержит то возвращает  пусто
	 * @param string $fileName
	 */
	public static function parseName(string $fileName):string {
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

}