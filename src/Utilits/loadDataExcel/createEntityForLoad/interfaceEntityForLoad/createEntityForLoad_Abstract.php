<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31.08.2016
 * Time: 22:08
 */

namespace App\Utilits\loadDataExcel\createEntityForLoad\interfaceEntityForLoad;


/**
 * Класс реализует методы которые ре
 *
 * Class createEntityForLoad_Abstract
 * @package LoadDataExcelBundle\Util\createEntityForLoad\
 */
abstract class createEntityForLoad_Abstract implements createEntityForLoad_interface
{

	/**
	 * преобразовывает число вида "111.11" в "111,11"
	 * @param string $str
	 * @return mixed
	 */
	public function getDouble(string $str)
	{
		if (is_numeric($str)){
			return str_replace ("." , "," , $str);
		} else
		{
			return $str;
		}
	}

	/**
	 * преобразовывает число как указание дати в объект дати
	 * для вставки в базу
	 * @param float $n
	 * @return \DateTime|float
	 */
	public function getDataType($n)
	{
		// иногда в реестре выданных НН вместо даты стоит "ноль" и вместо null
		// присваивается дата "2000-01-01" что етс ошибка
		if (is_numeric($n) and $n!=0) {
			return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($n, 'Europe/Kiev');
		} else
		{
			return new \DateTime('0000-00-00');
		}
	}

    /**
     * Проверяет явялется ли введенное число целым числом
     * @param $value
     * @return bool
     */
    public function isInteger($value):bool {
	    if (is_int($value)){
	        return true;
        } else{
	        return false;
        }
    }
}