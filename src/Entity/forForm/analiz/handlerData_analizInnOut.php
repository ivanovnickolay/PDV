<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11.02.2017
 * Time: 16:49
 */

namespace App\Entity\forForm\analiz;


use App\Model\getDataFromSQL\getDataOutINNByOne;
use Doctrine\ORM\EntityManager;
use App\Entity\forForm\analiz\analizInnOut;

/**
 * Класс - связующее звено между
 * - классом хранения данных analizInnOut
 * - и классом получения данных из ЕРПН и РПН филиалов
 *
 * Class handlerData_analizInnOut
 * @package AnalizPdvBundle\Entity\forForm\analiz
 */
class handlerData_analizInnOut
{
	/**
	 * @var EntityManager
	 */
	private $em;

	private $analiz;

	/**
	 * handlerData_analizInnOut constructor.
	 * @param EntityManager $entityManager
	 * @param analizInnOut $analizInnOut
	 */
	public function __construct(EntityManager $entityManager, analizInnOut $analizInnOut)
	{
		$this->analiz=$analizInnOut;
		$this->em=$entityManager;

	}

    /**
     * Получения метода выбора данных на основнании данных типа анализа
     * @param string $type
     * @return mixed
     */
	private function getNameMethodAnaliz()
	{
		$methodAnaliz =array(
			"E=R" => "getReestrEqualErpn",
			"E<>R" => "getErpnNoEqualReestr",
			"R<>E" => "getReestrNoEqualErpn",
		);
		if (array_key_exists($this->analiz->getTypeAnaliz(), $methodAnaliz)) {
			return $methodAnaliz[$this->analiz->getTypeAnaliz()];
		}
	}

	/**
	 * Получение класса выборки данных
	 * @return getDataOutINNByOne
	 */
	private function getClassData()
	{
		return new getDataOutINNByOne($this->em);
	}

	/**
	 * Получение данных анализа
	 *
	 * @return string
	 */
	public function getAnalizData()
	{
		$class=$this->getClassData();
		$nameMethod=$this->getNameMethodAnaliz();
		$month=$this->analiz->getMonthCreate();
		$year=$this->analiz->getYearCreate();
		$sprBranch=$this->analiz->getNumMainBranch();
		$numMainBranch=$sprBranch->getNumMainBranch();
		$arr=call_user_func_array(array($class, $nameMethod), array($month,$year,$numMainBranch));
		return $arr;
		//return $class.$nameMethod($month,$year,$numMainBranch);
	}

	/**
	 * Получения  наименование типа анализа на основании данных типа анализа
	 * @return mixed
	 */
	public function getNameTypeAnaliz()
	{
		$nameType = array(
			'E=R'=>'те которые совпали и в ЕРПН и в Реестре' ,
			'E<>R'=>'те которые есть только в ЕРПН ',
			'R<>E' =>'те которые есть только в Реестре ',
		);
		if (array_key_exists($this->analiz->getTypeAnaliz(), $nameType)) {
			return $nameType[$this->analiz->getTypeAnaliz()];
		}
	}

	/**
	 *  Получение реального номера филиала
	 * @return mixed
	 */
	public function getNumBranch()
	{
		$sprBranch = $this->analiz->getNumMainBranch();
		return $sprBranch->getNumMainBranch();
	}
}