<?php


namespace App\Utilits\Analiz;



use App\Utilits\Analiz\Exception\noCorrectDataException;
use DateTime as DateTimeAlias;
use Exception;

/**
 * Класс предназначен для возвращения нужных дат и периодов
 * при формировании  расширенных запросов по праву на НО и НК с учетом опаздавших РКЕ и ПНЕ
 *
 *  - пример использования если workDateForSQL::monthAnaliz = 5 и  workDateForSQL::yearAnaliz = 2016 то
 *      - workDateForSQL::getMonthMinisOne = 4
 *      - workDateForSQL::getYearMinisOneMonth = 2016
 *      - workDateForSQL::getMonthMinisTwo = 3
 *      - workDateForSQL::getYearMinisTwoMonth = 2016
 *      - workDateForSQL::getMonthPlusOne = 6
 *      - workDateForSQL::getYearPlusOneMonth = 2016
 *      - workDateForSQL::getStartPeriodAnalizRke = "2016-05-01" начало текущего периода
 *      - workDateForSQL::getЕndPeriodAnalizRke = "2016-06-15"
 *      - workDateForSQL::getStartPeriodAnalizRkeMinusOne = "2016-05-16"
 *      - workDateForSQL::getЕndPeriodAnalizRkeMinusOne = "2016-05-31" конец текущего периода
 *      - workDateForSQL::getStartPeriodAnalizRkeMinusTwo = "2015-12-01"
 *      - workDateForSQL::getЕndPeriodAnalizRkeMinusTwo = "2015-03-31"
 */

class workDateForSQL
{
    /**
     * месяц анализа
     * @var int
     */
    private $monthAnaliz;


    protected $monthAnailzCorrect=["1","2","3","4","5","6","7","8","9","10","11","12"];
    /**
     * год анализа
     * @var int
     */
    private $yearAnaliz;

    protected $yearAnalizCorrect=["2015","2016","2017","2018","2019"];

    /**
     * проверка корректности дат
     * workDateForSQL constructor.
     * Дата меньше 12-2015 не допускается
     * @param int $month месяц в диапазоне 1..12
     * @param int $year год в диапазоне 2015..2017
     * @throws noCorrectDataException значения дат вне допустимого диапазона
     */
    public function __construct(int $month, int $year)
    {
        if (in_array($month, $this->monthAnailzCorrect)){
            $this->monthAnaliz = $month;
        } else{
            throw new noCorrectDataException("Номер месяца вне диапазона. Инициализация объекта не проведена ");
        }

        if (in_array($year, $this->yearAnalizCorrect)){
            $this->yearAnaliz = $year;
        } else{
            throw new noCorrectDataException("Номер года вне диапазона. Инициализация объекта не проведена ");
        }
        if ($this->monthAnaliz<12 and $this->yearAnaliz==2015){
            throw new noCorrectDataException("Дата меньше 12-2015 не допускается. Инициализация объекта не проведена ");
        }
    }

    /**
     * @return int
     */
    public function getYearAnaliz(): int
    {
        return $this->yearAnaliz;
    }

    /**
     * @return int
     */
    public function getMonthAnaliz(): int
    {
        return $this->monthAnaliz;
    }
    /**
     * Возвращает месяц создания документа предыдущий анализируемому
     *   - Если текущий период анализа 05-2016 возвращает 4
     *   - Если текущий период анализа 1-2016 возвращает 12
     *   - Если текущий период 12-2015 то не актуально и возвращаетм null
     * @return int|null
     */
    public function getMonthMinisOne()
    {
        if ($this->monthAnaliz==12 and $this->yearAnaliz==2015){
            return null;
        }
        switch ($this->monthAnaliz){
            case 1:
                return 12;
                break;
            default:
                return $this->monthAnaliz-1;
                break;
        }

    }

    /**
     * Возвращает год создания документа предыдущий анализируемому
     *  - Если год равен 2015 то null
     *  - Если месяц 12 и год 2015 то null
     *  - Если месяц равен 1 то уменьшении месяца на 1 месяц равен 12, следовательно надо уменьшить и год на единицу
     *  - Если месяц НЕ равен 1 то уменьшении месяца на 1 год не меняется
     * @return int|null
     */
    public function getYearMinisOneMonth()
    {
        if($this->monthAnaliz==12 and 2015==$this->yearAnaliz) {return null;}
        if ($this->monthAnaliz==1 and 2015!=$this->yearAnaliz){
            return $this->yearAnaliz-1;
        } else{
            return $this->yearAnaliz;
        }

    }
    /**
     * Возвращает месяц создания документа других периодов предшествующих анализируемому
     *  - если текущий период анализа 05-2016  возвращает 3
     *  - если текущий период анализа 01-2016  возвращает 11
     *  - если текущий период анализа 02-2016  возвращает 12
     *  - если текущий период анализа 03-2016  возвращает 1
     *  - если текущий период 12-2015 или 01-2016 то не актуально и возвращаетм null
     * @return int|null
     */
    public function getMonthMinisTwo()
    {
        if ((1==$this->monthAnaliz and 2016==$this->yearAnaliz)
            or (12== $this->monthAnaliz and 2015==$this->yearAnaliz)){
            return null;
        }
        switch ($this->monthAnaliz){
            case 1:
                return 11;
                break;
            case 2:
                return 12;
                break;
            default:
                return $this->monthAnaliz-2;
                break;
        }
    }

    /**
     * Возвращает год создания документа предыдущий анализируемому
     *  - Если год равен 2015 то null
     *  - Если месяц равен 1 или 2 то уменьшении месяца на 2 месяца равен 12 или 11, следовательно надо уменьшить и год на единицу
     *  - Если месяц НЕ равен 1 или 2 то уменьшении месяца на 2, год не меняется
     * @return int|null
     */
    public function getYearMinisTwoMonth()
    {
        if ((2016==$this->yearAnaliz and $this->monthAnaliz==1) or (2015==$this->yearAnaliz)){
            return null;
        }
        if (($this->monthAnaliz==1)or ($this->monthAnaliz==2)){
            return $this->yearAnaliz-1;
        } else{
            return $this->yearAnaliz;
        }

    }

    /**
     * Возвращает месяц создания документа следующий за анализируемым
     *  - если текущий период анализа 05-2016 то возвращает 6
     *  - если текущий период анализа 12-2016 то возвращает 1
     * @return int
     */
    public function getMonthPlusOne():int
    {
        switch ($this->monthAnaliz){
            case 12:
                return 1;
                break;
            default:
                return $this->monthAnaliz+1;
                break;
        }

    }



    /**
     * Возвращает год создания документа следующий за анализируемым
     * - Если месяц равен 12 то увеличении  месяца на 1 месяц равен 1, следовательно надо увеличить и год на единицу
     * - Если месяц НЕ равен 12 то увеличения месяца на 1 год не меняется
     * @return int
     */
    public function getYearPlusOneMonth()
    {
        if ($this->monthAnaliz==12){
            return $this->yearAnaliz+1;
        } else{
            return $this->yearAnaliz;
        }

    }

    /**
     * возвращает дату начала интервала анализа РКЕ в периоде - первый день месяца
     * - если текущий период анализа 05-2016 это 01-05-2016
     * @return string
     * @throws Exception
     */
    public function getStartPeriodAnalizRke(){
        $start = new DateTimeAlias();
        $start->setDate($this->yearAnaliz, $this->monthAnaliz, 1);
        return $start->format('Y-m-d');
    }

    /**
     * возвращает дату конца интервала анализа РКЕ в периоде - 15 день следующего месяца
     *  - если текущий период анализа 05-2016 то 15-06-2016
     * @return string
     * @throws Exception
     */
    public function getEndPeriodAnalizRke(){
        $end = new DateTimeAlias();
        $end->setDate($this->getYearPlusOneMonth(), $this->getMonthPlusOne(), 15);
        return $end->format('Y-m-d');
    }

    /**
     * возвращает дату начала интервала анализа РКЕ предыдущего периода создания документа - 16 день
     *  - если текущий период анализа 05-2016 то для документов созданных 04-2016 это 16-05-2016
     * @return string
     * @throws Exception
     */
    public function getStartPeriodAnalizRkeMinusOne(){
        // если возможно проанализировать данные предыдущего периода
        if (!is_null($this->getMonthMinisOne()) and !is_null($this->getYearMinisOneMonth()) ) {
            $start = new DateTimeAlias();
            $start->setDate($this->yearAnaliz, $this->monthAnaliz, 16);
            return $start->format('Y-m-d');
        } else{
            return null;
            //throw new noCorrectDataException("Вызов getStartPeriodAnalizRkeMinusOne при недопустимых значениях месяца и года");
        }

    }

    /**
     * возвращает дату конца интервала анализа РКЕ предыдущего периода - последний день текущего месяца
     *  - Если текущий период анализа 05-2016 то для документов созданных 04-2016 это 31-05-2016
     * @link http://php.net/manual/ru/datetime.modify.php
     * @link http://php.net/manual/ru/datetime.formats.relative.php
     * @return string
     * @throws Exception
     */
    public function getEndPeriodAnalizRkeMinusOne(){
        // если возможно проанализировать данные предыдущего периода
        if (!is_null($this->getMonthMinisOne()) and !is_null($this->getYearMinisOneMonth()) ) {
            $end = new DateTimeAlias();
            $end->setDate($this->yearAnaliz, $this->monthAnaliz, 15);
            //* @link http://php.net/manual/ru/datetime.formats.relative.php
            return $end->modify('last day of this month')->format('Y-m-d');
        } else{
            return null;
            //throw new noCorrectDataException("Вызов getЕndPeriodAnalizRkeMinusOne при недопустимых значениях месяца и года");
        }
    }

    /**
     * возвращает дату начала интервала анализа РКЕ предыдущих периодов создания документа -
     * 1 день работы ПАТ = 01-12-2015.
     * @return string
     * @throws Exception
     */
    public function getStartPeriodAnalizRkeMinusTwo(){
        // если возможно проанализировать данные предыдущего периода
        if (!is_null($this->getMonthMinisTwo()) and !is_null($this->getYearMinisTwoMonth()) ) {
            $start = new DateTimeAlias();
            $start->setDate(2015, 12, 1);
            return $start->format('Y-m-d');
        } else{
            return null;
            //throw new noCorrectDataException("Вызов getStartPeriodAnalizRkeMinusTwo при недопустимых значениях месяца и года");
        }

    }

    /**
     * возвращает дату конца интервала анализа РКЕ предыдущего периода - последний день текущего месяца
     *  - Если текущий период анализа 05-2016 то для документов созданных после 03-2016 это 31-03-2016
     * @link http://php.net/manual/ru/datetime.modify.php
     * @link http://php.net/manual/ru/datetime.formats.relative.php
     * @return string
     * @throws Exception
     */
    public function getEndPeriodAnalizRkeMinusTwo(){
        // если возможно проанализировать данные предыдущего периода
        if (!is_null($this->getMonthMinisTwo()) and !is_null($this->getYearMinisOneMonth()) ) {
            $end = new DateTimeAlias();
            $end->setDate($this->getYearMinisTwoMonth(),$this->getMonthMinisTwo(), 15);
            //* @link http://php.net/manual/ru/datetime.formats.relative.php
            return $end->modify('last day of this month')->format('Y-m-d');
        } else{
            return null;
            //throw new noCorrectDataException("Вызов getЕndPeriodAnalizRkeMinusTwo при недопустимых значениях месяца и года");
        }
    }

    /**
     *  формирирование массива arrayForBindValueWithPeriod с данными параметров запроса
     *  которые ЗАВИСЯТ от периода
     * @throws Exception
     */
    public function getArrayForBindValueWithPeriod():array {
        $arrayForBindValueWithPeriod = array();
        $arrayForBindValueWithPeriod["m"]=$this->getMonthAnaliz();
        $arrayForBindValueWithPeriod["y"]=$this->getYearAnaliz();

        $arrayForBindValueWithPeriod["dateBeginRKE"]=$this->getStartPeriodAnalizRke();
        $arrayForBindValueWithPeriod["dateEndRKE"]=$this->getEndPeriodAnalizRke();

        if(!is_null($this->getMonthMinisOne())and (!is_null($this->getYearMinisOneMonth()))){
            $arrayForBindValueWithPeriod["m_minusOne"]=$this->getMonthMinisOne();
            $arrayForBindValueWithPeriod["y_minusOne"]=$this->getYearMinisOneMonth();
            $arrayForBindValueWithPeriod["dataBegin_minusOne"]=$this->getStartPeriodAnalizRkeMinusOne();
            $arrayForBindValueWithPeriod["dataEnd_minusOne"]=$this->getEndPeriodAnalizRkeMinusOne();
        }

        if(!is_null($this->getMonthMinisTwo())and (!is_null($this->getYearMinisTwoMonth()))){
            $arrayForBindValueWithPeriod["dataBeginCreate_invoice"]=$this->getStartPeriodAnalizRkeMinusTwo();
            $arrayForBindValueWithPeriod["dataEndCreate_invoice"]=$this->getEndPeriodAnalizRkeMinusTwo();
            $arrayForBindValueWithPeriod["dataBegin_minusTwo"]=$this->getStartPeriodAnalizRke();
            $arrayForBindValueWithPeriod["dataEnd_minusTwo"]=$this->getEndPeriodAnalizRkeMinusOne();
        }
        return $arrayForBindValueWithPeriod;
    }

    /**
     * формирирование массива arrayForBindValueWithoutPeriod с данными параметров запроса
     *  которые НЕ зависят от периода
     */
    public function getArrayForBindValueWithoutPeriod():array {
        $arrayForBindValueWithoutPeriod = array();
        $arrayForBindValueWithoutPeriod["m"]=$this->getMonthAnaliz();
        $arrayForBindValueWithoutPeriod["y"]=$this->getYearAnaliz();
        return $arrayForBindValueWithoutPeriod;
    }


}