<?php

namespace App\Utilits\Analiz\prepareSQL;

use App\Utilits\Analiz\Exception\noCorrectDataException;
use App\Utilits\Analiz\Exception\noCorrectRoutingSearchException;
use PHPUnit\Framework\TestCase;

/**
 * Тестирования правильности формирования текстов запросов на выборку данных в зависимости от периода поиска данных
 * в ЕРПН и направления поиска
 * Class prepareSQLToErpnTest
 * @package App\Utilits\Analiz\prepareSQL
 */
class prepareSQLToErpnTest extends TestCase
{

    /**
     * Тестирование генерации исключения при вводе не верной даты поиска
     * @throws noCorrectDataException
     * @throws noCorrectRoutingSearchException
     */
    public function testException_noCorrectData()
    {
        $this->expectException(noCorrectDataException::class);
        prepareSQLToErpn::getPrepareSQL(15,2018,"ErpnIn");
    }

    /**
     * Тестирование генерации исключения при вводе не верного направления поиска
     * @throws noCorrectDataException
     * @throws noCorrectRoutingSearchException
     */
    public function testException_noCorrectRouting()
    {
        $this->expectException(noCorrectRoutingSearchException::class);
        prepareSQLToErpn::getPrepareSQL(1,2018,"fsfsf");
    }

    /**
     * Тест правильности возврата текста запросов путем контроля уникальных фрагментов запросов
     * @throws noCorrectDataException
     * @throws noCorrectRoutingSearchException
     */
    public function testgetPrepareSQL_minusTwoMonth()
    {
        $this->assertNotFalse(strpos(
            prepareSQLToErpn::getPrepareSQL(11,2016,"ErpnIn"),
            ' FROM erpn_in ei
        WHERE 
             (MONTH(ei.date_create_invoice) = :m
             AND YEAR(ei.date_create_invoice) = :y);'
        ));
        $this->assertNotFalse(strpos(
            prepareSQLToErpn::getPrepareSQL(11,2016,"ErpnOut"),
            '(eo.date_create_invoice BETWEEN CAST(:dataBeginCreate_invoice AS DATE)  AND CAST(:dataEndCreate_invoice AS DATE)
                        AND eo.type_invoice_full="РКЕ"
                        AND eo.date_reg_invoice BETWEEN CAST(:dataBegin_minusTwo AS DATE)  AND CAST(:dataEnd_minusTwo AS DATE));'
        ));
    }

    /**
     * Тест правильности возврата текста запросов путем контроля уникальных фрагментов запросов
     * @throws noCorrectDataException
     * @throws noCorrectRoutingSearchException
     */
    public function testgetPrepareSQL_minusOneMonth()
    {
        $this->assertNotFalse(strpos(
            prepareSQLToErpn::getPrepareSQL(1,2016,"ErpnIn"),
            ' FROM erpn_in ei
        WHERE 
             (MONTH(ei.date_create_invoice) = :m
             AND YEAR(ei.date_create_invoice) = :y);'
        ));
        $this->assertNotFalse(strpos(
            prepareSQLToErpn::getPrepareSQL(1,2016,"ErpnOut"),
            ' (MONTH(eo.date_create_invoice) = :m_minusOne
            AND YEAR(eo.date_create_invoice) = :y_minusOne
            AND eo.type_invoice_full="РКЕ"
            AND eo.date_reg_invoice BETWEEN CAST(:dataBegin_minusOne AS DATE)  AND CAST(:dataEnd_minusOne AS DATE));'
        ));
    }
    /**
     * Тест правильности возврата текста запросов путем контроля уникальных фрагментов запросов
     * @throws noCorrectDataException
     * @throws noCorrectRoutingSearchException
     */
    public function testgetPrepareSQL()
    {

        $this->assertNotFalse(strpos(
            prepareSQLToErpn::getPrepareSQL(12,2015,"ErpnIn"),
            ' FROM erpn_in ei
        WHERE 
             (MONTH(ei.date_create_invoice) = :m
             AND YEAR(ei.date_create_invoice) = :y);'
        ));
        $this->assertNotFalse(strpos(
            prepareSQLToErpn::getPrepareSQL(12,2015,"ErpnOut"),
            ' FROM erpn_out eo
        WHERE 
             ((MONTH(eo.date_create_invoice) = :m
             AND YEAR(eo.date_create_invoice) = :y)
             AND eo.type_invoice_full="ПНЕ")
          OR
            ((MONTH(eo.date_create_invoice) = :m
             AND YEAR(eo.date_create_invoice) = :y)
            AND eo.type_invoice_full="РКЕ"
            AND eo.date_reg_invoice BETWEEN CAST(:dateBeginRKE AS DATE)  AND CAST(:dateEndRKE AS DATE));'
        ));
    }
}
