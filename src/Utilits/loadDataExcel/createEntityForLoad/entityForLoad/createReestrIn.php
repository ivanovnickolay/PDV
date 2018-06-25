<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31.08.2016
 * Time: 22:10
 */

 namespace App\Utilits\loadDataExcel\createEntityForLoad\entityForLoad;

use App\Entity\ReestrbranchIn;
use App\Utilits\loadDataExcel\createEntityForLoad\interfaceEntityForLoad\createEntityForLoad_Abstract;


/**
 * Класс предназначенный для создания сущности ReestrbranchIn() на основании данных полученных
 * из входящего массива
 * Class createReestrIn
  */
class createReestrIn extends createEntityForLoad_Abstract
{
	/**
	 * @var ReestrbranchIn
	 */
	private $reestrIn;

	/**
	 * создание сущности ReestrbranchIn() на основании данных полученных из входящего массива
	 * @param array $arr
	 * @return ReestrbranchIn
	 */
	public function createReestr (array $arr)
	{
		$this->reestrIn=new ReestrbranchIn();
			$this->reestrIn->setMonth($arr[0][79]);
				$this->reestrIn->setYear($arr[0][87]);
					$this->reestrIn->setNumBranch($arr[0][66]);
						$this->reestrIn->setDateGetInvoice($this->getDataType($arr[0][105]));
							$this->reestrIn->setDateCreateInvoice($this->getDataType($arr[0][126]));
								$this->reestrIn->setNumInvoice($arr[0][106]);
		$this->reestrIn->setTypeInvoiceFull($arr[0][134]);
			$this->reestrIn->setNameClient($arr[0][108]);
				$this->reestrIn->setInnClient($arr[0][109]);
					$this->reestrIn->setZagSumm($arr[0][111]);
						$this->reestrIn->setBaza20($arr[0][113]);
								$this->reestrIn->setPdv20($arr[0][116]);
		$this->reestrIn->setBaza7($arr[0][114]);
			$this->reestrIn->setPdv7($arr[0][117]);
				$this->reestrIn->setBaza0($arr[0][115]);
					$this->reestrIn->setPdv0($arr[0][118]);
						$this->reestrIn->setBazaZvil($arr[0][120]);
							$this->reestrIn->setPdvZvil($arr[0][95]);
								$this->reestrIn->setBazaNeGos($arr[0][98]);
									$this->reestrIn->setPdvNeGos($arr[0][101]);
		$this->reestrIn->setBazaZaMezhi($arr[0][103]);
			$this->reestrIn->setPdvZaMezhi($arr[0][104]);
				$this->reestrIn->setRkeDateCreateInvoice($this->getDataType($arr[0][122]));
					$this->reestrIn->setRkeNumInvoice($arr[0][123]);
						$this->reestrIn->setRkePidstava($arr[0][124]);
							$this->reestrIn->setKeyField();
		return $this->reestrIn;
	}
	/**
	 * обнуляем сущность что бы можно было еще раз ее создать не создавая заново класс
	 */
	public function unsetReestr()
	{
		unset($this->reestrIn);
	}
}