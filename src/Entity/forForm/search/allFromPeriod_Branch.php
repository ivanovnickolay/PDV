<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.12.2016
 * Time: 20:06
 */

namespace App\Entity\forForm\search;

use App\Entity\forForm\validationConstraint\ContainsNumDoc;
use App\Form\allFromPeriod_BranchForm;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Класс реализует форму поиска данных в ЕРПН и Реестре
 *  документов выданных или полученных ВСП или филиалом
 *  по следующим параметрам
	 *   месяц создания документа
	 *   год создания документа
	 *   направление поиска
	 *   номер структурного подразделения
	 *   номер филиала

 * Условия поиска данных :
 *      Реестры есть только по филиалам следовательно  поиск в Реестрах производится ТОЛЬКО если указан номер филиала
 *

 *
 * public static function loadValidatorMetadata  валидация данных класса
 * @uses allFromPeriod_BranchForm класс формы отображения
 *
 * Class allFromPeriod_Branch
 * @package AnalizPdvBundle\Entity\forForm\search
 */
class allFromPeriod_Branch extends searchAbstract
{
	/**
	 * allFromPeriod_Branch constructor.
	 */
	public function __construct()
	{
		// устанавливаем первоначальные значения полей
		// месяц и год равны текущему месяцу и году
		parent::__construct();
		$today = getdate();
		$this->numBranch="000";
		$this->numMainBranch="000";
	}

	/**
	 * @var string номер структурного подразделения
	 */
	private $numBranch;

	/**
	 * @return string
	 */
	public function getNumBranch (): string
	{
		return $this->numBranch;
	}

	/**
	 * @param string $numBranch
	 */
	public function setNumBranch ($numBranch)
	{
		if (empty($numBranch) or is_null($numBranch))
		{
			$this->numBranch ="000";
		} else
		{
			$this->numBranch = $numBranch;
		}

	}

	/**
	 * @var string номер филиала
	 */
	private $numMainBranch;

	/**
	 * @return string
	 */
	public function getNumMainBranch (): string
	{
		return $this->numMainBranch;
	}

	/**
	 * @param string $numMainBranch
	 */
	public function setNumMainBranch ($numMainBranch)
	{
		if (empty($numMainBranch) or is_null($numMainBranch))
		{
			$this->numMainBranch = "000";
		} else
		{
			$this->numMainBranch = $numMainBranch;
		}

	}

	/**
	 * валидация формы
	 * покрыта тестами
	 * @param ClassMetadata $metadata
	 */
	public static function loadValidatorMetadata(ClassMetadata $metadata)
	{
		// проверка номера месяца
	/*
	 	$metadata->addPropertyConstraint('monthCreate', new Assert\Range(array(
			'min'        => 1,
			'max'        => 12,
			'minMessage' => 'Введенный месяц не должен быть меньше {{ limit }}',
			'maxMessage' => 'Введенный месяц не должен быть больше {{ limit }}',
		)));
		// проверка года
		$metadata->addPropertyConstraint('yearCreate', new Assert\Range(array(
			'min'        => 2015,
			'max'        => 2017,
			'minMessage' => 'Введенный год не должен быть меньше {{ limit }}',
			'maxMessage' => 'Введенный год не должен быть больше {{ limit }}',
		)));
	*/
		// проверка номера структурного подразделения numBranch
		   // по длине
		$metadata->addPropertyConstraint('numBranch', new Assert\Length(array(
			'min'        => 3,
			'max'        => 3,
			'minMessage' => 'Номер структурного подразделения не должен быть меньше {{ limit }} символов',
			'maxMessage' => 'Номер структурного подразделения не должен быть больше {{ limit }} символов',
		)));
		   // должно содержать только цифры
		$metadata->addPropertyConstraint('numBranch', new Assert\Type(array(
			'type'    => 'digit',
			'message' => 'Номер структурного подразделения {{ value }} должен содержать только цифры .',
		)));
		// проверка номера филиала numMainBranch
		  // по длине
		$metadata->addPropertyConstraint('numMainBranch', new Assert\Length(array(
			'min'        => 3,
			'max'        => 3,
			'minMessage' => 'Номер филиала не должен быть меньше {{ limit }} символов',
			'maxMessage' => 'Номер филиала не должен быть больше {{ limit }} символов',
		)));
		// должно содержать только цифры
		$metadata->addPropertyConstraint('numMainBranch', new Assert\Type(array(
			'type'    => 'digit',
			'message' => 'Номер филиала {{ value }} должен содержать только цифры .',
		)));

	}

	/**
	 * формирование массива для передачи в запрос
	 *
	 * ЭТО ВАЖНО !!! название ключей массива - нзвание полей класса ErpnOut
	 *
	 * @param $typeRouteSearch
	 * @return array
	 */
	public function getArrayFromSearch($typeRouteSearch)
	{
		$result = array();
		$result["monthCreateInvoice"]=$this->monthCreate;
		$result["yearCreateInvoice"]=$this->yearCreate;
		if ($this->numBranch!="000")
		{
			$result["numBranchVendor"]=$this->numBranch;
		}
		if ($this->numMainBranch!="000")
		{
			$result["numMainBranch"]=$this->numMainBranch;
		}
		return $result;
	}

	/**
	 * Формирование массива для поиска в ЕРПН
	 * @return array
	 */
	public function getArrayFromSearchErpn()
	{
		$result = array();
		$result["monthCreateInvoice"]=$this->monthCreate;
		$result["yearCreateInvoice"]=$this->yearCreate;
		if ($this->numBranch!="000")
		{
			$result["numBranchVendor"]=$this->numBranch;
		}
		if ($this->numMainBranch!="000")
		{
			$result["numMainBranch"]=$this->numMainBranch;
		}



		return $result;
	}

	/**
	 * * Формирование массива для поиска в Реестрах
	 * @return array
	 */
	public function getArrayFromSearchReestr()
	{
		$result = array();


		return $result;


	}

}