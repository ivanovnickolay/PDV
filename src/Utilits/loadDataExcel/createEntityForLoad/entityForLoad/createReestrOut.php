<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31.08.2016
 * Time: 22:10
 */

namespace App\Utilits\loadDataExcel\createEntityForLoad\entityForLoad;
//namespace AnalizPdvBundle\Entity;


use App\Entity\ReestrbranchOut;
use App\Utilits\loadDataExcel\createEntityForLoad\interfaceEntityForLoad\createEntityForLoad_Abstract;

;

/**
 * Класс предназначенный для создания сущности ReestrbranchOut() на основании данных полученных
 * из входящего массива
 * Class createReestrOut
 */
class createReestrOut extends createEntityForLoad_Abstract
{
	/**
	 * @var ReestrbranchOut
	 */
	private $reestrOut;

	/**
	 * создание сущности ReestrbranchOut() на основании данных полученных из входящего массива
     * массив - это одна строка прочитанная из файла РПН экспортированного с Медка
	 * @param array $arr
	 * @return ReestrbranchOut
	 */
	public function createReestr (array $arr)
	{
		$this->reestrOut=new ReestrbranchOut();
			$this->reestrOut->setMonth($arr[0][79]);
				$this->reestrOut->setYear($arr[0][87]);
					$this->reestrOut->setNumBranch($arr[0][66]);
						$this->reestrOut->setDateCreateInvoice($this->getDataType($arr[0][99]));
								$this->reestrOut->setNumInvoice($arr[0][100]);
		$this->reestrOut->setTypeInvoiceFull($arr[0][121]);
			$this->reestrOut->setTypeInvoice($arr[0][119]);
			$this->reestrOut->setNameClient($arr[0][103]);
				$this->reestrOut->setInnClient($arr[0][104]);
					$this->reestrOut->setZagSumm($arr[0][106]);
						$this->reestrOut->setBaza20($arr[0][107]);
								$this->reestrOut->setPdv20($arr[0][109]);
		$this->reestrOut->setBaza7($arr[0][108]);
			$this->reestrOut->setPdv7($arr[0][110]);
				$this->reestrOut->setBaza0($arr[0][111]);
					$this->reestrOut->setBazaZvil($arr[0][94]);
						$this->reestrOut->setBazaNeObj($arr[0][97]);
							$this->reestrOut->setBazaZaMezhiTovar($arr[0][95]);
			$this->reestrOut->setBazaZaMezhiPoslug($arr[0][96]);
				$this->reestrOut->setRkeDateCreateInvoice($this->getDataType($arr[0][112]));
					$this->reestrOut->setRkeNumInvoice($arr[0][114]);
						$this->reestrOut->setRkePidstava($arr[0][115]);
							$this->reestrOut->setKeyField();
		return $this->reestrOut;
	}

	/**
	 * обнуляем сущность что бы можно было еще раз ее создать не создавая заново класс
	 */
	public function unsetReestr()
	{
		unset($this->reestrOut);
	}
}