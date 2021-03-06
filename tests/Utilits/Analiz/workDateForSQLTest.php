<?php

namespace App\Utilits\Analiz;

use App\Utilits\Analiz\Exception\noCorrectDataException;
use DateTime as DateTimeAlias;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование класса для работы с датами при формирировании запростов на выборку данных
 * Class workDateForSQLTest
 * @package App\Utilits\Analiz
 *
 */
class workDateForSQLTest extends TestCase
{
    /**
     * @throws noCorrectDataException
     */
    public function testCreateClass()
    {
        $obj = new workDateForSQL(5,2018);
        $this->assertInstanceOf(workDateForSQL::class,$obj);
    }
    /**
     * @throws noCorrectDataException
     */
    public function testCreateClassNoCorrectData_11_2015()
    {
        $this->expectException(noCorrectDataException::class);
        $this->expectExceptionMessage("Дата меньше 12-2015 не допускается. Инициализация объекта не проведена ");
        $obj = new workDateForSQL(11,2015);
        $this->assertInstanceOf(workDateForSQL::class,$obj);
    }
    /**
     * @throws noCorrectDataException
     */
    public function testCreateClassNoCorrectData_14_2017()
    {
        $this->expectException(noCorrectDataException::class);
        $this->expectExceptionMessage("Номер месяца вне диапазона. Инициализация объекта не проведена ");
        $obj = new workDateForSQL(14,2017);
        $this->assertInstanceOf(workDateForSQL::class,$obj);
    }

    /**
     * @throws noCorrectDataException
     */
    public function testCreateClassNoCorrectData_1_2020()
    {
        $this->expectException(noCorrectDataException::class);
        $this->expectExceptionMessage("Номер года вне диапазона. Инициализация объекта не проведена ");
        $obj = new workDateForSQL(1,2020);
        $this->assertInstanceOf(workDateForSQL::class,$obj);
    }

    /**
     * @throws noCorrectDataException
     * @throws Exception
     */
    public function testGetAllFor_12_2015()
    {
        $obj = new workDateForSQL(12,2015);
        $this->assertInstanceOf(workDateForSQL::class,$obj);
        $this->assertEquals(12,$obj->getMonthAnaliz());
        $this->assertEquals(2015,$obj->getYearAnaliz());
        $this->assertEquals(null,$obj->getMonthMinisOne());
        $this->assertEquals(null,$obj->getYearMinisOneMonth());
        $this->assertEquals(null,$obj->getMonthMinisTwo());
        $this->assertEquals(null,$obj->getYearMinisTwoMonth());
        $this->assertEquals(1,$obj->getMonthPlusOne());
        $this->assertEquals(2016,$obj->getYearPlusOneMonth());

        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearAnaliz(),$obj->getMonthAnaliz(), 1)->format('Y-m-d'),
            $obj->getStartPeriodAnalizRke());
        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearPlusOneMonth(),$obj->getMonthPlusOne(), 15)->format('Y-m-d'),
            $obj->getEndPeriodAnalizRke());

        $this->assertEquals(
            null,
            $obj->getStartPeriodAnalizRkeMinusOne());
        $this->assertEquals(
            null,
            $obj->getEndPeriodAnalizRkeMinusOne());

        $this->assertEquals(
            null,
            $obj->getStartPeriodAnalizRkeMinusTwo());
        $this->assertEquals(
            null,
            $obj->getEndPeriodAnalizRkeMinusTwo());

        $arrayForBindValueWithoutPeriod = $obj->getArrayForBindValueWithoutPeriod();
        $this->assertEquals(12,$arrayForBindValueWithoutPeriod["m"]);
        $this->assertEquals(2015,$arrayForBindValueWithoutPeriod["y"]);

        $arrayForBindValueWithPeriod = $obj->getArrayForBindValueWithPeriod();
        $this->assertEquals(12,$arrayForBindValueWithPeriod["m"]);
        $this->assertEquals(2015,$arrayForBindValueWithPeriod["y"]);
        $this->assertEquals("2015-12-01",$arrayForBindValueWithPeriod["dateBeginRKE"]);
        $this->assertEquals("2016-01-15",$arrayForBindValueWithPeriod["dateEndRKE"]);
        $this->assertFalse(key_exists('m_minusOne',$arrayForBindValueWithPeriod));
        $this->assertFalse(key_exists('y_minusOne',$arrayForBindValueWithPeriod));
        $this->assertFalse(key_exists('dataBegin_minusOne',$arrayForBindValueWithPeriod));
        $this->assertFalse(key_exists('dataEnd_minusOne',$arrayForBindValueWithPeriod));
        $this->assertFalse(key_exists('dataEnd_minusOne',$arrayForBindValueWithPeriod));
        $this->assertFalse(key_exists('dataBeginCreate_invoice',$arrayForBindValueWithPeriod));
        $this->assertFalse(key_exists('dataEndCreate_invoice',$arrayForBindValueWithPeriod));
        $this->assertFalse(key_exists('dataBegin_minusTwo',$arrayForBindValueWithPeriod));
        $this->assertFalse(key_exists('dataEnd_minusTwo',$arrayForBindValueWithPeriod));

    }

    /**
     * @throws noCorrectDataException
     * @throws Exception
     */
    public function testGetAllFor_1_2016()
    {
        $obj = new workDateForSQL(1,2016);
        $this->assertInstanceOf(workDateForSQL::class,$obj);
        $this->assertEquals(1,$obj->getMonthAnaliz());
        $this->assertEquals(2016,$obj->getYearAnaliz());
        $this->assertEquals(12,$obj->getMonthMinisOne());
        $this->assertEquals(2015,$obj->getYearMinisOneMonth());
        $this->assertEquals(null,$obj->getMonthMinisTwo());
        $this->assertEquals(null,$obj->getYearMinisTwoMonth());
        $this->assertEquals(2,$obj->getMonthPlusOne());
        $this->assertEquals(2016,$obj->getYearPlusOneMonth());

        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearAnaliz(),$obj->getMonthAnaliz(), 1)->format('Y-m-d'),
            $obj->getStartPeriodAnalizRke());
        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearPlusOneMonth(),$obj->getMonthPlusOne(), 15)->format('Y-m-d'),
            $obj->getEndPeriodAnalizRke());

        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearAnaliz(),$obj->getMonthAnaliz(), 16)->format('Y-m-d'),
            $obj->getStartPeriodAnalizRkeMinusOne());
        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearAnaliz(),$obj->getMonthAnaliz(), 16)->modify('last day of this month')->format('Y-m-d'),
            $obj->getEndPeriodAnalizRkeMinusOne());

        $this->assertEquals(
           null,
            $obj->getStartPeriodAnalizRkeMinusTwo());
        $this->assertEquals(
            null,
            $obj->getEndPeriodAnalizRkeMinusTwo());

        $arrayForBindValueWithoutPeriod = $obj->getArrayForBindValueWithoutPeriod();
        $this->assertEquals(1,$arrayForBindValueWithoutPeriod["m"]);
        $this->assertEquals(2016,$arrayForBindValueWithoutPeriod["y"]);

        $arrayForBindValueWithPeriod = $obj->getArrayForBindValueWithPeriod();
        $this->assertEquals(1,$arrayForBindValueWithPeriod["m"]);
        $this->assertEquals(2016,$arrayForBindValueWithPeriod["y"]);
        $this->assertEquals("2016-01-01",$arrayForBindValueWithPeriod["dateBeginRKE"]);
        $this->assertEquals("2016-02-15",$arrayForBindValueWithPeriod["dateEndRKE"]);
        $this->assertEquals(12,$arrayForBindValueWithPeriod["m_minusOne"]);
        $this->assertEquals(2015,$arrayForBindValueWithPeriod["y_minusOne"]);
        $this->assertEquals("2016-01-16",$arrayForBindValueWithPeriod["dataBegin_minusOne"]);
        $this->assertEquals("2016-01-31",$arrayForBindValueWithPeriod["dataEnd_minusOne"]);
        $this->assertEquals("2016-01-31",$arrayForBindValueWithPeriod["dataEnd_minusOne"]);
        $this->assertFalse(key_exists('dataBeginCreate_invoice',$arrayForBindValueWithPeriod));
        $this->assertFalse(key_exists('dataEndCreate_invoice',$arrayForBindValueWithPeriod));
        $this->assertFalse(key_exists('dataBegin_minusTwo',$arrayForBindValueWithPeriod));
        $this->assertFalse(key_exists('dataEnd_minusTwo',$arrayForBindValueWithPeriod));
//        $this->assertEquals(1,$arrayForBindValueWithPeriod["dataEndCreate_invoice"]);
//        $this->assertEquals(1,$arrayForBindValueWithPeriod["dataBegin_minusTwo"]);
//        $this->assertEquals(1,$arrayForBindValueWithPeriod["dataEnd_minusTwo"]);
   }
    /**
     * @throws noCorrectDataException
     * @throws Exception
     */
    public function testGetAllFor_2_2016()
    {
        $obj = new workDateForSQL(2,2016);
        $this->assertInstanceOf(workDateForSQL::class,$obj);
        $this->assertEquals(2,$obj->getMonthAnaliz());
        $this->assertEquals(2016,$obj->getYearAnaliz());
        $this->assertEquals(1,$obj->getMonthMinisOne());
        $this->assertEquals(2016,$obj->getYearMinisOneMonth());
        $this->assertEquals(12,$obj->getMonthMinisTwo());
        $this->assertEquals(2015,$obj->getYearMinisTwoMonth());
        $this->assertEquals(3,$obj->getMonthPlusOne());
        $this->assertEquals(2016,$obj->getYearPlusOneMonth());

        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearAnaliz(),$obj->getMonthAnaliz(), 1)->format('Y-m-d'),
            $obj->getStartPeriodAnalizRke());
        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearPlusOneMonth(),$obj->getMonthPlusOne(), 15)->format('Y-m-d'),
            $obj->getEndPeriodAnalizRke());

        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearAnaliz(),$obj->getMonthAnaliz(), 16)->format('Y-m-d'),
            $obj->getStartPeriodAnalizRkeMinusOne());
        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearAnaliz(),$obj->getMonthAnaliz(), 16)->modify('last day of this month')->format('Y-m-d'),
            $obj->getEndPeriodAnalizRkeMinusOne());

        $this->assertEquals(
            (new DateTimeAlias())->setDate(2015,12, 1)->format('Y-m-d'),
            $obj->getStartPeriodAnalizRkeMinusTwo());
        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearMinisTwoMonth(),$obj->getMonthMinisTwo(), 16)->modify('last day of this month')->format('Y-m-d'),
            $obj->getEndPeriodAnalizRkeMinusTwo());


        $arrayForBindValueWithoutPeriod = $obj->getArrayForBindValueWithoutPeriod();
        $this->assertEquals(2,$arrayForBindValueWithoutPeriod["m"]);
        $this->assertEquals(2016,$arrayForBindValueWithoutPeriod["y"]);

        $arrayForBindValueWithPeriod = $obj->getArrayForBindValueWithPeriod();
        $this->assertEquals(2,$arrayForBindValueWithPeriod["m"]);
        $this->assertEquals(2016,$arrayForBindValueWithPeriod["y"]);
        $this->assertEquals("2016-02-01",$arrayForBindValueWithPeriod["dateBeginRKE"]);
        $this->assertEquals("2016-03-15",$arrayForBindValueWithPeriod["dateEndRKE"]);
        $this->assertEquals(1,$arrayForBindValueWithPeriod["m_minusOne"]);
        $this->assertEquals(2016,$arrayForBindValueWithPeriod["y_minusOne"]);
        $this->assertEquals("2016-02-16",$arrayForBindValueWithPeriod["dataBegin_minusOne"]);
        $this->assertEquals("2016-02-29",$arrayForBindValueWithPeriod["dataEnd_minusOne"]);
        $this->assertEquals("2015-12-01",$arrayForBindValueWithPeriod["dataBeginCreate_invoice"]);
        $this->assertEquals("2015-12-31",$arrayForBindValueWithPeriod["dataEndCreate_invoice"]);
        $this->assertEquals("2016-02-01",$arrayForBindValueWithPeriod["dataBegin_minusTwo"]);
        $this->assertEquals("2016-02-29",$arrayForBindValueWithPeriod["dataEnd_minusTwo"]);
    }

    /**
     * @throws noCorrectDataException
     * @throws Exception
     */
    public function testGetAllFor_12_2016()
    {
        $obj = new workDateForSQL(12,2016);
        $this->assertInstanceOf(workDateForSQL::class,$obj);
        $this->assertEquals(12,$obj->getMonthAnaliz());
        $this->assertEquals(2016,$obj->getYearAnaliz());
        $this->assertEquals(11,$obj->getMonthMinisOne());
        $this->assertEquals(2016,$obj->getYearMinisOneMonth());
        $this->assertEquals(10,$obj->getMonthMinisTwo());
        $this->assertEquals(2016,$obj->getYearMinisTwoMonth());
        $this->assertEquals(1,$obj->getMonthPlusOne());
        $this->assertEquals(2017,$obj->getYearPlusOneMonth());

        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearAnaliz(),$obj->getMonthAnaliz(), 1)->format('Y-m-d'),
            $obj->getStartPeriodAnalizRke());
        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearPlusOneMonth(),$obj->getMonthPlusOne(), 15)->format('Y-m-d'),
            $obj->getEndPeriodAnalizRke());

        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearAnaliz(),$obj->getMonthAnaliz(), 16)->format('Y-m-d'),
            $obj->getStartPeriodAnalizRkeMinusOne());
        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearAnaliz(),$obj->getMonthAnaliz(), 16)->modify('last day of this month')->format('Y-m-d'),
            $obj->getEndPeriodAnalizRkeMinusOne());

        $this->assertEquals(
            (new DateTimeAlias())->setDate(2015,12, 1)->format('Y-m-d'),
            $obj->getStartPeriodAnalizRkeMinusTwo());
        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearMinisTwoMonth(),$obj->getMonthMinisTwo(), 16)->modify('last day of this month')->format('Y-m-d'),
            $obj->getEndPeriodAnalizRkeMinusTwo());

        $arrayForBindValueWithoutPeriod = $obj->getArrayForBindValueWithoutPeriod();
        $this->assertEquals(12,$arrayForBindValueWithoutPeriod["m"]);
        $this->assertEquals(2016,$arrayForBindValueWithoutPeriod["y"]);

        $arrayForBindValueWithPeriod = $obj->getArrayForBindValueWithPeriod();
        $this->assertEquals(12,$arrayForBindValueWithPeriod["m"]);
        $this->assertEquals(2016,$arrayForBindValueWithPeriod["y"]);
        $this->assertEquals("2016-12-01",$arrayForBindValueWithPeriod["dateBeginRKE"]);
        $this->assertEquals("2017-01-15",$arrayForBindValueWithPeriod["dateEndRKE"]);
        $this->assertEquals(11,$arrayForBindValueWithPeriod["m_minusOne"]);
        $this->assertEquals(2016,$arrayForBindValueWithPeriod["y_minusOne"]);
        $this->assertEquals("2016-12-16",$arrayForBindValueWithPeriod["dataBegin_minusOne"]);
        $this->assertEquals("2016-12-31",$arrayForBindValueWithPeriod["dataEnd_minusOne"]);
        $this->assertEquals("2015-12-01",$arrayForBindValueWithPeriod["dataBeginCreate_invoice"]);
        $this->assertEquals("2016-10-31",$arrayForBindValueWithPeriod["dataEndCreate_invoice"]);
        $this->assertEquals("2016-12-01",$arrayForBindValueWithPeriod["dataBegin_minusTwo"]);
        $this->assertEquals("2016-12-31",$arrayForBindValueWithPeriod["dataEnd_minusTwo"]);
    }

    /**
     * @throws noCorrectDataException
     * @throws Exception
     */
    public function testGetAllFor_11_2016()
    {
        $obj = new workDateForSQL(11,2016);
        $this->assertInstanceOf(workDateForSQL::class,$obj);
        $this->assertEquals(11,$obj->getMonthAnaliz());
        $this->assertEquals(2016,$obj->getYearAnaliz());
        $this->assertEquals(10,$obj->getMonthMinisOne());
        $this->assertEquals(2016,$obj->getYearMinisOneMonth());
        $this->assertEquals(9,$obj->getMonthMinisTwo());
        $this->assertEquals(2016,$obj->getYearMinisTwoMonth());
        $this->assertEquals(12,$obj->getMonthPlusOne());
        $this->assertEquals(2016,$obj->getYearPlusOneMonth());

        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearAnaliz(),$obj->getMonthAnaliz(), 1)->format('Y-m-d'),
            $obj->getStartPeriodAnalizRke());
        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearPlusOneMonth(),$obj->getMonthPlusOne(), 15)->format('Y-m-d'),
            $obj->getEndPeriodAnalizRke());

        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearAnaliz(),$obj->getMonthAnaliz(), 16)->format('Y-m-d'),
            $obj->getStartPeriodAnalizRkeMinusOne());
        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearAnaliz(),$obj->getMonthAnaliz(), 16)->modify('last day of this month')->format('Y-m-d'),
            $obj->getEndPeriodAnalizRkeMinusOne());

        $this->assertEquals(
            (new DateTimeAlias())->setDate(2015,12, 1)->format('Y-m-d'),
            $obj->getStartPeriodAnalizRkeMinusTwo());
        $this->assertEquals(
            (new DateTimeAlias())->setDate($obj->getYearMinisTwoMonth(),$obj->getMonthMinisTwo(), 16)->modify('last day of this month')->format('Y-m-d'),
            $obj->getEndPeriodAnalizRkeMinusTwo());

        $arrayForBindValueWithoutPeriod = $obj->getArrayForBindValueWithoutPeriod();
        $this->assertEquals(11,$arrayForBindValueWithoutPeriod["m"]);
        $this->assertEquals(2016,$arrayForBindValueWithoutPeriod["y"]);

        $arrayForBindValueWithPeriod = $obj->getArrayForBindValueWithPeriod();
        $this->assertEquals(11,$arrayForBindValueWithPeriod["m"]);
        $this->assertEquals(2016,$arrayForBindValueWithPeriod["y"]);
        $this->assertEquals("2016-11-01",$arrayForBindValueWithPeriod["dateBeginRKE"]);
        $this->assertEquals("2016-12-15",$arrayForBindValueWithPeriod["dateEndRKE"]);
        $this->assertEquals(10,$arrayForBindValueWithPeriod["m_minusOne"]);
        $this->assertEquals(2016,$arrayForBindValueWithPeriod["y_minusOne"]);
        $this->assertEquals("2016-11-16",$arrayForBindValueWithPeriod["dataBegin_minusOne"]);
        $this->assertEquals("2016-11-30",$arrayForBindValueWithPeriod["dataEnd_minusOne"]);
        $this->assertEquals("2015-12-01",$arrayForBindValueWithPeriod["dataBeginCreate_invoice"]);
        $this->assertEquals("2016-09-30",$arrayForBindValueWithPeriod["dataEndCreate_invoice"]);
        $this->assertEquals("2016-11-01",$arrayForBindValueWithPeriod["dataBegin_minusTwo"]);
        $this->assertEquals("2016-11-30",$arrayForBindValueWithPeriod["dataEnd_minusTwo"]);
    }
}
