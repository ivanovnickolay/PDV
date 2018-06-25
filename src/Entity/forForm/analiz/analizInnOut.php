<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25.01.2017
 * Time: 21:42
 */

namespace App\Entity\forForm\analiz;

use App\Entity\ReestrbranchOut;
use App\Entity\Repository\ReestrBranch_out;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class analizInnOut
 * @package App\Entity\forForm\analiz
 */
class analizInnOut
{
	/**
	 * месяц создания документа
	 * @var string
	 */
	protected $monthCreate;
	/**
	 * Допустимые значения $monthCreate
	 * @var array
	 * @uses setMonthCreate здесь устанавливается значение
	 */
	protected $correctMonthCreate;

	/**
	 * год создания документа
	 * @var string
	 */
	protected  $yearCreate;

	/**
	 * допустимые значение $yearCreate
	 * @var array
	 */
	protected $correctYearCreate=["2015","2016","2017"];

	/**
	 * номер филиала для анализа
	 * @var string
	 */
	protected $numMainBranch;

	/**
	 * тип анализа данных
	 * - те которые совпали и в ЕРПН и в Реестре ( $typeAnaliz ='E=R")
	 * - те которые есть только в ЕРПН ( $typeAnaliz ='E<>R")
	 * - те которые есть только в Реестре ( $typeAnaliz ='R<>E")
	 * @var
	 */
	protected $typeAnaliz;

	/**
	 * допустимые значение $typeAnaliz
	 * @var array
	 */
	protected $correcttypeAnaliz=["E=R","E<>R","R<>E"];

	/**
	 * @var EntityManager
	 */
	private $entiryManager;

	/**
	 * analizInnOut constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		// устанавливаем первоначальные значения полей для указания в форме
		// месяц и год равны текущему месяцу и году
		$today = getdate();
		$this->monthCreate=$today["mon"];
		$this->yearCreate=$today["year"];
		$this->entiryManager=$em;
	}

	/**
	 * @return string
	 */
	final public function getMonthCreate ()
	{
		return  $this->monthCreate;
	}

	/**
	 * Проверка данных перед присваиванием
	 *  - разрешаются только допустимые значения
	 *  - все остальные - игнорируются
	 * @param string $monthCreate
	 */
	final public function setMonthCreate ($monthCreate)
	{
		// установим значение по умолчанию - текущий месяц
		$today = getdate();
		$this->monthCreate=$today["mon"];
		$this->correctMonthCreate=range('1', '12');
		// если полученное значение в диапазоне допустимых значений то
		// присвоим полученное значение переменной
		if (in_array($monthCreate, $this->correctMonthCreate)){
			$this->monthCreate = $monthCreate;
		}
		// если переданно не корректное значение - переменная равна значению по умолчанию
	}


	/**
	 * @return string
	 */
	final public function getYearCreate ()
	{
		return $this->yearCreate;
	}

	/**
	 * Проверка данных перед присваиванием
	 *  - разрешаются только допустимые значения
	 *  - все остальные - игнорируются
	 * @param string $yearCreate
	 */
	final public function setYearCreate ($yearCreate)
	{
		// установим значение по умолчанию - текущий месяц
		$today = getdate();
		$this->yearCreate=$today["year"];
		// если полученное значение в диапазоне допустимых значений то
		// присвоим полученное значение переменной
		if (in_array($yearCreate, $this->correctYearCreate)){
			$this->yearCreate = $yearCreate;
		}
		// если переданно не корректное значение - переменная равна значению по умолчанию
	}

	/**
	 * @return string
	 */
	public function getNumMainBranch ()
	{
		return $this->numMainBranch;
	}

	/**
	 * @param string $numMainBranch
	 */
	public function setNumMainBranch ($numMainBranch)
	{
		if (empty($numMainBranch) or is_null($numMainBranch)){
			$this->numMainBranch = "000";
		} else{
			$this->numMainBranch = $numMainBranch;
		}
	}

	/**
	 * @return mixed
	 */
	public function getTypeAnaliz()
	{
		return $this->typeAnaliz;
	}

	/**
	 * @param mixed $typeAnaliz
	 */
	public function setTypeAnaliz($typeAnaliz)
	{
		// если полученное значение в диапазоне допустимых значений то
		// присвоим полученное значение переменной
		if (in_array($typeAnaliz, $this->correcttypeAnaliz)){
			$this->typeAnaliz = $typeAnaliz;
		} else{
			// если передано не корректное значение то
			$this->typeAnaliz ="E=R";
		}
	}
	/**
	 * валидация формы
	 * покрыта тестами
	 * @param ClassMetadata $metadata
	 */
	public static function loadValidatorMetadata(ClassMetadata $metadata)
	{
		// проверка номера филиала numMainBranch
		// по длине
		/*
		$metadata->addPropertyConstraint('numMainBranch', new Assert\NotEqualTo(array(
			'value'    => '000',
			'message' => 'Номер филиала {{ value }} не может быть пустым.',
		)));

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
		*/
		$metadata->addConstraint(new Assert\Callback('validate'));

	}

	/**
	 * Проверка соответсвия перида поиска номеру филиала
	 *  в данном периоде должен быть реестр выданных НН с указанным номером филилала
	 * @param ExecutionContextInterface $context
	 * @param $payload
	 */
	public function validate(ExecutionContextInterface $context, $payload)
	{
		// Если заполнено поле  dateCreateDoc
		$repos=$this->entiryManager->getRepository('App:ReestrbranchOut');

		if (!$repos->is_NumMainBranchToPeriod($this->getMonthCreate(), $this->getYearCreate(), $this->getNumMainBranch())) {
				$context->buildViolation("В указанном периоде НЕТ данных указанного филиала !")
					->atPath('numMainBranch')
					->addViolation();
			}

	}
}