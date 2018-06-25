<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 08.05.2017
 * Time: 23:54
 */

namespace App\Utilits\loadDataExcel\createEntityForLoad\interfaceEntityForLoad;


/**
 *  Реализация интерфейса для создания сущностей на основании данных из полученного массива
 * Interface createEntityForLoad_interface
 * @package LoadDataExcelBundle\Util\createEntityForLoad
 */

interface createEntityForLoad_interface
{
	/**
	 *  Создает сущносность из массива данных
	 * @param array $arr
	 * @return mixed
	 */
	public function createReestr(array $arr);

	/**
	 * Обнуляет сущность послее ее сохранения
	 * @return mixed
	 */
	public function unsetReestr();


}