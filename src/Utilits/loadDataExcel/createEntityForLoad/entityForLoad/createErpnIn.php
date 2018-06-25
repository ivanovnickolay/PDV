<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12.03.2017
 * Time: 18:33
 */

namespace App\Utilits\loadDataExcel\createEntityForLoad\entityForLoad;


use App\Entity\ErpnIn;

use App\Utilits\loadDataExcel\createEntityForLoad\interfaceEntityForLoad\createEntityForLoad_Abstract;


/**
 * Создание сущности ErpnIn на основании полученного массива значений
 *
 * Class createErpnOut
 * @package AnalizPdvBundle\Utilits\LoadInvoice\createEntity
 */
class createErpnIn extends createEntityForLoad_Abstract
{
	private $E_In;

	/**
     *  Создает сущносность из массива данных
     * @param array $arr
     * @return mixed
     */
    public function createReestr(array $arr)
    {
        $this->E_In=new ErpnIn();


        return $this->E_In;
    }

    /**
     * Обнуляет сущность послее ее сохранения
     */
    public function unsetReestr()
    {
        unset($this->E_In);
    }
}