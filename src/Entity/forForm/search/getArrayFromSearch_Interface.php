<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 07.01.2017
 * Time: 13:25
 */

namespace App\Entity\forForm\search;


/**
 *
 * интерфейс для формирования массива с данынми для поиска данных в ЕРПН и реестрах
 *
 * Interface getArrayFromSearch
 * @package AnalizPdvBundle\Entity\forForm\search
 */
interface getArrayFromSearch_Interface
{
	/**
	 * Формирование массива для поиска в ЕРПН
	 * @return array
	 */
	public function getArrayFromSearchErpn():array;

	/**
	 * Формирование массива для поиска в Реестрах
	 * @return array
	 */
	public function getArrayFromSearchReestr():array;


}