<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26.12.2016
 * Time: 22:30
 */

namespace App\Entity\forForm\search;
use App\Tests\Entity\forForm\searchAbstractTest;


/**
 * Абстрактный класс для реализации поиска данных в реестрах и ЕРПН
 *  суть класса - реализация типовых операций для не допущения их дублирования в
 *  наследуемых классах
 * Типовые операции
 *  - получение данных у месяце документа - не допускается ввод не верного месяца из диапазона 1-12
 *  - получение данных у годе документа - не допускается ввод не верного года из диапазона 2015-2017
 *  - получение направления поиска информации - не допускается ввод не верного направления поиска
 *
 *  ВАЖНО!!!
 *  Валидация данных не требуется - все проверки проводятся при получении данных в set фукнциях
 *
 *  @uses searchAbstractTest тест класса
 *
 * Class searchAbstract
 * @package AnalizPdvBundle\Entity\forForm\search
 */
 class searchAbstract
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
	 * направление поиска данных
	 *  -  обязательства (Out)
	 *  -  кредит (In)
	 * @var string
	 */
	protected $routeSearch;

	/**
	 * Допустимые значения $routeSearch
	 * @var array
	 */
	protected $correctRouteSearch=["Обязательства","Кредит"];


	/**
	 * searchAbstract constructor.
	 */
	 public function __construct()
	{
		// устанавливаем первоначальные значения полей для указания в форме
		// месяц и год равны текущему месяцу и году
		$today = getdate();
		$this->monthCreate=$today["mon"];
		$this->yearCreate=$today["year"];
		$this->routeSearch="Обязательства";
	}

	/**
	 * @return string
	 */
	final public function getMonthCreate ()
	{
		return $this->monthCreate;
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
		if (in_array($monthCreate, $this->correctMonthCreate))
		{
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
		if (in_array($yearCreate, $this->correctYearCreate))
		{
			$this->yearCreate = $yearCreate;
		}
		// если переданно не корректное значение - переменная равна значению по умолчанию
	}
	/**
	 * @return string
	 */
	 final public function getRouteSearch()
	{
		return $this->routeSearch;
	}

	/**
	 * @param string $routeSearch
	 */
	 final public function setRouteSearch( $routeSearch)
	{
		// если полученное значение в диапазоне допустимых значений то
		// присвоим полученное значение переменной
		if (in_array($routeSearch, $this->correctRouteSearch))
		{
			$this->routeSearch = $routeSearch;
		} else
		{
			// если передано не корректное значение то
			$this->routeSearch ="Обязательства";
		}

	}


 }