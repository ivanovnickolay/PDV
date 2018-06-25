<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 02.09.2016
 * Time: 14:33
 */

namespace App\Utilits\loadDataExcel\ValidEntity;


use App\Entity\Errorloadreestr;

abstract class interfaceValidEntity
{
	protected $key_field ;
	public $error;
	protected $typeReestr;
	protected $entity;
	protected $numBranch;

	/**
	 * interfaceValidEntity constructor.
	 * @param string $typeReestr значение типа реестра который провереяется
	 */
	public function __construct (string $typeReestr='')
	{
		$this->typeReestr=$typeReestr;
		//$this->error='';
	}

	/**
	 * получение записи в сущности Errorloadreestr с сформированными ошибками по записи реестра
	 * @return Errorloadreestr|null
	 */
	public function getErrorEntity()
	{
		if ((!empty($this->error))) {
			$error=new Errorloadreestr();
			$error->setKeyField($this->key_field);
			$error->setError($this->error);
			$error->setNumbranch($this->numBranch);
			$error->setTypereestr($this->typeReestr);
			return $error;
		}
		return null;

	}

	abstract public function validEntity($entity);
}