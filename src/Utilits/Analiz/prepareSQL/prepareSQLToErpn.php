<?php


namespace App\Utilits\Analiz\prepareSQL;


use App\Utilits\Analiz\Exception\noCorrectRoutingSearchException;
use App\Utilits\Analiz\workDateForSQL;


/**
 * Формирование текстов запросов на выборку данных в зависимости от периода поиска данных
 * в ЕРПН и направления поиска
 * Class prepareSQLToErpn
 * @package App\Utilits\Analiz\prepareSQL
 */
class prepareSQLToErpn
{

    private static $sqlErpnIn= /** @lang MySQL */
        'SELECT
          ei.num_invoice, 
          ei.date_create_invoice,
          ei.type_invoice_full, 
          ei.inn_client, 
          ei.name_client, 
          ei.suma_invoice, 
          ei.pdvinvoice, 
          ei.baza_invoice,
          ei.name_vendor, 
          ei.num_branch_vendor, 
          ei.key_field
        FROM erpn_in ei
        WHERE 
             (MONTH(ei.date_create_invoice) = :m
             AND YEAR(ei.date_create_invoice) = :y);';

    private static $sqlErpnInMinusOneMonth=/** @lang MySQL */
        'SELECT
          ei.num_invoice, 
          ei.date_create_invoice,
          ei.type_invoice_full, 
          ei.inn_client, 
          ei.name_client, 
          ei.suma_invoice, 
          ei.pdvinvoice, 
          ei.baza_invoice,
          ei.name_vendor, 
          ei.num_branch_vendor, 
          ei.key_field
        FROM erpn_in ei
        WHERE 
             (MONTH(ei.date_create_invoice) = :m
             AND YEAR(ei.date_create_invoice) = :y);';

    private static $sqlErpnInMinusTwoMonth=/** @lang MySQL */
        'SELECT
          ei.num_invoice, 
          ei.date_create_invoice,
          ei.type_invoice_full, 
          ei.inn_client, 
          ei.name_client, 
          ei.suma_invoice, 
          ei.pdvinvoice, 
          ei.baza_invoice,
          ei.name_vendor, 
          ei.num_branch_vendor, 
          ei.key_field
        FROM erpn_in ei
        WHERE 
             (MONTH(ei.date_create_invoice) = :m
             AND YEAR(ei.date_create_invoice) = :y);';

    /**
     * Алгоритм формирования запросов к ErpnOut
     *  база $sqlErpnOut
     *       - все ПНЕ текущего периода без учета времени регистрации
     *       - все РКЕ текущего периода с регистрацией от первого дня текущего месяце до 15 дня месяца следующего за текущим
     * Этот запрос выполнятеся только для периода 12-2015 !!!
     * @var string
     */
    private static $sqlErpnOut=/** @lang MySQL */
        'SELECT
          eo.num_invoice, 
          eo.date_create_invoice,
          eo.type_invoice_full, 
          eo.inn_client, 
          eo.name_client, 
          eo.suma_invoice, 
          eo.pdvinvoice, 
          eo.baza_invoice,
          eo.name_vendor, 
          eo.num_branch_vendor, 
          eo.key_field, 
          eo.month_create_invoice, 
          eo.year_create_invoice
        FROM erpn_out eo
        WHERE 
             ((MONTH(eo.date_create_invoice) = :m
             AND YEAR(eo.date_create_invoice) = :y
             AND eo.type_invoice_full="ПНЕ")
          OR
            ((MONTH(eo.date_create_invoice) = :m
             AND YEAR(eo.date_create_invoice) = :y)
            AND eo.type_invoice_full="РКЕ"
            AND eo.date_reg_invoice BETWEEN CAST(:dateBeginRKE AS DATE)  AND CAST(:dateEndRKE AS DATE));';

    /**
     * Алгоритм формирования $sqlErpnOutMinusOneMonth
     * - к запросу сфомированному $sqlErpnOut добавляем
     * - все РКЕ прошлого периода (если он есть) с регистрацией от 16 дня текущего месяце до последнего дня текущего месяца
     * * Этот запрос выполнятеся только для периода 01-2016 !!!
     * @var string
     */
    private static $sqlErpnOutMinusOneMonth=/** @lang MySQL */
        'SELECT
          eo.num_invoice, 
          eo.date_create_invoice,
          eo.type_invoice_full, 
          eo.inn_client, 
          eo.name_client, 
          eo.suma_invoice, 
          eo.pdvinvoice, 
          eo.baza_invoice,
          eo.name_vendor, 
          eo.num_branch_vendor, 
          eo.key_field, 
          eo.month_create_invoice, 
          eo.year_create_invoice
        FROM erpn_out eo
        WHERE 
             (MONTH(eo.date_create_invoice) = :m
             AND YEAR(eo.date_create_invoice) = :y
             AND eo.type_invoice_full="ПНЕ")
          OR
            (MONTH(eo.date_create_invoice) = :m
             AND YEAR(eo.date_create_invoice) = :y
            AND eo.type_invoice_full="РКЕ"
            AND eo.date_reg_invoice BETWEEN CAST(:dateBeginRKE AS DATE)  AND CAST(:dateEndRKE AS DATE))
          OR
            (MONTH(eo.date_create_invoice) = :m_minusOne
            AND YEAR(eo.date_create_invoice) = :y_minusOne
            AND eo.type_invoice_full="РКЕ"
            AND eo.date_reg_invoice BETWEEN CAST(:dataBegin_minusOne AS DATE)  AND CAST(:dataEnd_minusOne AS DATE));';

    /**
   * Алгоритм формирования $sqlErpnInMinusOneMonth
   * - к запросу сфомированному $sqlErpnIn добавляем
   * - все РКЕ прошлых периодов (если они есть) с регистрацией от 1 дня текущего месяце до последнего дня текущего месяца
   * Этот запрос выполнятеся для всех периодов после 01-2016 начиная с 02-2016 !!!
     * @var string
   */
    private static $sqlErpnOutMinusTwoMonth= /** @lang MySQL */
        'SELECT
          eo.num_invoice, 
          eo.date_create_invoice,
          eo.type_invoice_full, 
          eo.inn_client, 
          eo.name_client, 
          eo.suma_invoice, 
          eo.pdvinvoice, 
          eo.baza_invoice,
          eo.name_vendor, 
          eo.num_branch_vendor, 
          eo.key_field, 
          eo.month_create_invoice, 
          eo.year_create_invoice
        FROM erpn_out eo
        WHERE 
             (MONTH(eo.date_create_invoice) = :m
             AND YEAR(eo.date_create_invoice) = :y
             AND eo.type_invoice_full="ПНЕ")
          OR
            (MONTH(eo.date_create_invoice) = :m
             AND YEAR(eo.date_create_invoice) = :y
            AND eo.type_invoice_full="РКЕ"
            AND eo.date_reg_invoice BETWEEN CAST(:dateBeginRKE AS DATE)  AND CAST(:dateEndRKE AS DATE))
          OR
            (MONTH(eo.date_create_invoice) = :m_minusOne
            AND YEAR(eo.date_create_invoice) = :y_minusOne
            AND eo.type_invoice_full="РКЕ"
            AND eo.date_reg_invoice BETWEEN CAST(:dataBegin_minusOne AS DATE)  AND CAST(:dataEnd_minusOne AS DATE))
          OR 
           (eo.date_create_invoice BETWEEN CAST(:dataBeginCreate_invoice AS DATE)  AND CAST(:dataEndCreate_invoice AS DATE)
                        AND eo.type_invoice_full="РКЕ"
                        AND eo.date_reg_invoice BETWEEN CAST(:dataBegin_minusTwo AS DATE)  AND CAST(:dataEnd_minusTwo AS DATE));';

    private static $correctRouting = ['ErpnIn','ErpnOut'];

    /**
     * @param int $monthSearch
     * @param int $yearSearch
     * @param string $routingSearch
     * @return string
     * @throws \App\Utilits\Analiz\Exception\noCorrectDataException
     * @throws noCorrectRoutingSearchException
     */
    public static function getPrepareSQL(int $monthSearch, int $yearSearch, string $routingSearch):string {

        if (!in_array($routingSearch,self::$correctRouting)){
            throw new noCorrectRoutingSearchException("Не корректное направление поиска данных ".$routingSearch);
        }
        $obj = new workDateForSQL($monthSearch,$yearSearch);

        if ($obj->getMonthMinisTwo()!=null){
            if ('ErpnIn'==$routingSearch){
                return static::$sqlErpnInMinusTwoMonth;
            }else{
                return static::$sqlErpnOutMinusTwoMonth;
            }
        }
        if ($obj->getMonthMinisOne()!=null){
            if ('ErpnIn'==$routingSearch){
                return static::$sqlErpnInMinusOneMonth;
            }else{
                return static::$sqlErpnOutMinusOneMonth;
            }
        }else{
            if ('ErpnIn'==$routingSearch){
                return static::$sqlErpnIn;
            }else{
                return static::$sqlErpnOut;
            }
        }

    }
}