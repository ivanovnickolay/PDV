<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25.12.2016
 * Time: 20:17
 */

namespace App\Entity\forForm\search;
use App\Entity\forForm\validationConstraint\ContainsNumDoc;
use App\Entity\forForm\docFromParamTest;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

// todo покрыть валидацию тестами !!!
/**
 * Класс реализует форму поиска данных в ЕРПН и Реестре
 * по следующим параметрам
 *   месяц создания документа
 *   год создания документа
 *   номер документа
 *   тип документа = "ПНЕ" или "РКЕ"
 *   ИПН контрагента
 * Class docFromParam
 * @package AnalizPdvBundle\Entity\forForm\search
 */
class docFromParam extends searchAbstract
{
	/**
	 * docFromParam constructor.
	 */
	public function __construct()
		{
			parent::__construct();
			$this->setTypeDoc("ПНЕ");
			$this->setNumDoc(null);
			// null что бы корректно отображалась форма ввода
			$this->setINN(null);
		}

	/**
	 * номер документа
	 * @var
	 */
	private $numDoc;

	/**
	 * тип документа = "ПНЕ" или "РКЕ"
	 * @var
	 */
	private $typeDoc;

	/**
	 * ИПН контрагента
	 * @var
	 */
	private $INN;

	/**
	 * дата создания документа
	 * @var
	 */
	private $dateCreateDoc;

	/**
	 * @return mixed
	 */
	public function getDateCreateDoc()
	{
		return $this->dateCreateDoc;
	}

	/**
	 * @param mixed $dateCreateDoc
	 */
	public function setDateCreateDoc($dateCreateDoc)
	{
		if (new \DateTime("0000-00-00")==$dateCreateDoc)
		{
			$this->dateCreateDoc = null;
		} else {
			$this->dateCreateDoc = $dateCreateDoc;
		}

	}

	
	/**
	 * @return mixed
	 */
	public function getNumDoc()
	{
		return $this->numDoc;
	}

	/**
	 *  если получено пустое значение при заполнении формы то 
	 *  присвоим условное значение "0" 
	 * @param mixed $numDoc
	 */
	public function setNumDoc($numDoc)
	{
		if (empty($numDoc)) {
			$this->numDoc = null;
		} else
		{
			$this->numDoc = $numDoc;
		}
		
	}

	/**
	 * @return mixed
	 */
	public function getTypeDoc()
	{
		return $this->typeDoc;
	}

	/**
	 * Если получен не типовой тип документа перечисленный в
	 * $typeDocArray то присвоим условное значение "0"
	 * @param mixed $typeDoc
	 */
	public function setTypeDoc($typeDoc)
	{
		$typeDocArray=["ПНЕ","РКЕ"];
		if (in_array($typeDoc, $typeDocArray))
		{
			$this->typeDoc = $typeDoc;
		} else
		{
			$this->typeDoc = "0";
		}

	}

	/**
	 * @return integer
	 */
	public function getINN()
	{
		return $this->INN;
	}

	/**
	 *  Даннное поле должно содержать только цифры
	 *  поэтому если при проверке окажеться что переданное значение содержит не только цифры
	 *  то присвоим условное значение " " (пробел)
	 * @link http://php.net/manual/ru/function.ctype-digit.php
	 * @param mixed $INN
	 */
	public function setINN($INN)
	{
		/*if (ctype_digit((string) $INN)) {
			$this->INN = $INN;
		} else {
			$this->INN =" ";
		}
		*/
		$this->INN = $INN;
	}

	/**
	 * валидация формы
	 * покрыт тестами
	 * @uses docFromParamTest.testValidator тест
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
		// проверка номера документа
		//$metadata->addPropertyConstraint("numDoc", new ContainsNumDoc());
		/*
		$metadata->addPropertyConstraint("numDoc", new Assert\NotEqualTo(
			array(
				'value' => "0",
				'message'=>'Не верный номер документа.'
			)
		));
		*/
		// проверка типа документам
		$metadata->addPropertyConstraint("typeDoc", new Assert\NotEqualTo(
			array(
				'value' => "0",
				'message'=>'Не верный тип документа. Тип документа должен быть или ПНЕ или РКЕ'
			)
		));
		// проверка ИНН
		/*
		 $metadata->addPropertyConstraint("INN", new Assert\NotEqualTo(
			array(
				'value' => " ",
				'message'=>'Не верный ИНН контрагента'
			)
		));
		*/
//		$metadata->addPropertyConstraint('INN', new Assert\Length(array(
//			'min'        => 0,
//			'max'        => 12,
//			'maxMessage' => 'Длина ИНН не может быть более {{ limit }} цифр',
//		)));
//		$metadata->addPropertyConstraint('INN', new Assert\Type(array(
//			'type'    => 'digit',
//			'message' => 'ИНН {{ value }} должен содержать только цифры .',
//		)));
	$metadata->addConstraint(new Assert\Callback('validate'));
	}

	/**
	 * Проверка соответсвия перида поиска данных дате создания документа
	 *  периоды должны совпадать
	 * @param ExecutionContextInterface $context
	 * @param $payload
	 */
	public function validate(ExecutionContextInterface $context, $payload)
	{
	    //если поле номер документа заполнено
        $message = '"%string%" - не верный номер документа ';
	   if (!is_null($this->numDoc)){
        if(!$this->isValidNumDoc()){
            //if (preg_match("/[^0-9\/]/",$this->numDoc, $matches)) {
                $context->buildViolation($message)
                    ->setParameter('%string%', $this->numDoc)
                    ->atPath("numDoc")
                    ->addViolation();
           }
        }
		// Если заполнено поле  dateCreateDoc
		if(!is_null($this->dateCreateDoc)){
       	// получем массив содержащий дату документа
			$dateCreate=getdate($this->dateCreateDoc->getTimestamp());
			// ЕСЛИ месяц или год не совпадают = генерируем ошибку
			if (($dateCreate['mon']!=$this->getMonthCreate()) or($dateCreate['year']!=$this->getYearCreate()))
			{
				$context->buildViolation("Период поиска документа и дата создания документа должны совпадать !")
					->atPath('dateCreateDoc')
						->addViolation();
			}
		}
		 if (!is_null($this->INN)){
		     if (!ctype_digit($this->INN)){
                 $context->buildViolation("ИНН ".$this->INN." должен содержать только цифры ")
                     ->atPath('INN')
                     ->addViolation();
             }else {
		         $arrayCorrectlenght = array(10,12);
                 $lenght = strlen($this->INN);
                 if (!in_array($lenght,$arrayCorrectlenght)) {
                     $context->buildViolation("ИНН ".$this->INN." должен иметь длину или 10 или 12 символов !")
                         ->atPath('INN')
                         ->addViolation();
                 }
             }

         }
	}

    /**
     * @return bool
     */
    private function isValidNumDoc(){
	    $nd = $this->numDoc;
	    $lenghtStr = strlen($nd);
	    $firstSlach = strpos($nd,"/");
	    if (false==$firstSlach){
            //если в строке не / значит строка должна содержать только цифрв
            if (!ctype_digit($nd)) {
               return false;
            } else {
                return true;
            }
        }else{
	        // если есть один / проверим есть ли // (два подряд)
            if(($firstSlach+1)==$lenghtStr){return false;}
            if("/"==$nd[$firstSlach+1]){
                // если "//"
                if(($firstSlach+2)==$lenghtStr){return false;}
                if("/"==$nd[$firstSlach+2]){
                    // если "///" - номер не валидный
                    return false;
                }
                // делим строку на две части
                $arraySplit = explode('//',$nd);
                if((!ctype_digit($arraySplit[0]))and(!ctype_digit($arraySplit[1]))){
                    return true;
                }
            }else{
                // в строке только один "/" - номер не валидный
                return false;
            }
            if (preg_match("/[^0-9\/]/",$nd)) {
                return false;
            } else {
                return true;
            }
        }
    }
	/**
	 * формирование массива для передачи в запрос
	 *
	 * ЭТО ВАЖНО !!! название ключей массива - нзвание полей класса ErpnOut
	 *
	 * @return array
	 */
	public function getArrayFromSearch()
	{
		$result = array();
		$result["monthCreateInvoice"]=$this->monthCreate;
		$result["yearCreateInvoice"]=$this->yearCreate;
		if ($this->numDoc!="0")
		{
			$result["numBranchVendor"]=$this->numDoc;
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
		$result["typeInvoiceFull"]=$this->typeDoc;

		if ($this->INN!="")
		{
			$result["innClient"]=$this->INN;
		}
		if ($this->numDoc!="0")
		{
			$result["numInvoice"]=$this->numDoc;
		}
		if (!is_null($this->dateCreateDoc))
		{
			$result["dateCreateInvoice"]=$this->dateCreateDoc;
		}


		return $result;
	}
}