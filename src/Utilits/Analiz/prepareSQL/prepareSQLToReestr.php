<?php


namespace App\Utilits\Analiz\prepareSQL;


use App\Utilits\Analiz\Exception\noCorrectRoutingSearchException;


class prepareSQLToReestr
{

    private static $sqlReestrIn='SELECT
                    month,
                    year,
                    num_branch,
                  num_invoice, 
                  date_create_invoice,
                  type_invoice_full,
                  inn_client, 
                  name_client, 
                  zag_summ,
                  (baza_20+baza_7+baza_0+baza_zvil+baza_ne_gos+baza_za_mezhi) AS baza,
                  (pdv_20+pdv_7+ pdv_0 + pdv_zvil + pdv_ne_gos + pdv_za_mezhi) AS pdvinvoice,
                  key_field
                  FROM reestrbranch_in
                 WHERE month = :m
                  AND year = :y';

    private static $sqlReestrOut='SELECT
                    month,
                    year,
                    num_branch,
                  num_invoice, 
                  date_create_invoice,
                  type_invoice_full,
                  inn_client, 
                  name_client, 
                  zag_summ,
                  (baza_20+baza_7+baza_0+baza_zvil+baza_ne_obj+baza_za_mezhi_tovar+baza_za_mezhi_poslug) AS baza,
                  (pdv_20+pdv_7) AS pdvinvoice,
                  key_field
                  FROM reestrbranch_out
                  WHERE month = :m
                  AND year = :y;';

    private static $correctRouting = ['ReestrIn','ReestrOut'];

    /**
     *
     * @param string $routingSearch
     * @return string
     * @throws noCorrectRoutingSearchException
     */
    public static function getPrepareSQL(string $routingSearch):string {

        if (!in_array($routingSearch,self::$correctRouting)){
            throw new noCorrectRoutingSearchException("Не корректное направление поиска данных ".$routingSearch);
        }

            if ('ReestrIn'==$routingSearch){
                return static::$sqlReestrIn;
            }else{
                return static::$sqlReestrOut;
            }
        }
}