<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21.03.2019
 * Time: 00:11
 */

namespace App\Services\searchDataFromParam;


use App\Entity\ReestrbranchIn;
use App\Entity\ReestrbranchOut;


/**
 * Сервис организации поиска данных в Реестарх выданных и получнных налоговых накладных
 *
 * Class searchReestrFromParam
 * @package App\Services
 */
class searchReestrFromParam extends searchFromParamAbstract
{


    /**
     * получает результат запроса из базы
     * преобразовывает полученный масссив объктов в двухмерный массив простых данных
     * в зависимости от того, какая сущность обрабатывается вызываются анонимныые фукнции
     * для суммирования данных полей сущности
     *
     * @return array
     */
    protected function getArraySearchData():array {
        /**
         * анонимная функиция для вычисления суммы баз налогообложения
         * @param ReestrbranchOut $data
         * @return float
         */
        $summBazaReestrOut = function (ReestrbranchOut $data)
        {
            return $data->getBaza20()+$data->getBaza7()+$data->getBaza0()+$data->getBazaNeObj()+$data->getBazaZaMezhiPoslug()+$data->getBazaZaMezhiTovar()+$data->getBazaZvil();
        };

        /**
         * анонимная функиция для вычисления сумм ПДВ
         * @param ReestrbranchOut $data
         * @return float
         */
        $summPdvReestrOut = function (ReestrbranchOut $data)
        {
            return $data->getPdv20()+$data->getPdv7();
        };

        /**
         * анонимная функиция для вычисления суммы баз налогообложения
         * @param ReestrbranchIn $data
         * @return float
         */
        $summBazaReestrIn = function (ReestrbranchIn $data)
        {
            return $data->getBaza7()
                +$data->getBazaZaMezhi()
                + $data->getBazaZvil()
                + $data->getBaza20()
                +$data->getBaza0()
                +$data->getBazaNeGos();
        };

        /**
         * анонимная функиция для вычисления сумм ПДВ
         * @param ReestrbranchIn $data
         * @return float
         */
        $summPdvReestrIn = function (ReestrbranchIn $data)
        {
            return $data->getPdv20()
                +$data->getPdv7()
                +$data->getPdv0()
                +$data->getPdvNeGos()
                +$data->getPdvZaMezhi()
                +$data->getPdvZvil();
        };

        $searchData = $this->getSearchData();
        $arraySearchData = array();
        if (!is_null($searchData)){
            $counter = 0;
            /** @var ReestrbranchIn|ReestrbranchOut $sd */
            foreach ($searchData as $sd){
                $arraySearchData[$counter]["NumInvoice"]=$sd->getNumInvoice();
                $arraySearchData[$counter]["DateCreateInvoice"]=$sd->getDateCreateInvoice();
                $arraySearchData[$counter]["TypeInvoiceFull"]=$sd->getTypeInvoiceFull();
                $arraySearchData[$counter]["InnClient"]=$sd->getInnClient();
                $arraySearchData[$counter]["NameClient"]=$sd->getNameClient();
                $arraySearchData[$counter]["NumBranchReestr"]=$sd->getNumBranch();
               // $arraySearchData[$counter]["NameVendor"]=$sd->getNameClient();
                $arraySearchData[$counter]["SumaInvoice"]= $sd->getZagSumm();
                if ( $sd instanceof ReestrbranchOut){
                    $arraySearchData[$counter]["BazaInvoice"]=$summBazaReestrOut($sd);
                    $arraySearchData[$counter]["Pdvinvoice"]=$summPdvReestrOut($sd);
                }
                if ($sd instanceof ReestrbranchIn){
                    $arraySearchData[$counter]["BazaInvoice"]=$summBazaReestrIn($sd);
                    $arraySearchData[$counter]["Pdvinvoice"]=$summPdvReestrIn($sd);
                }

                $counter++;
            }
        }
        return $arraySearchData;
    }
    /**
     * получает результат запроса из базы
     * преобразовывает полученный масссив объктов в двухмерный массив простых данных
     * @uses getArraySearchData для получения данных из базы анных
     * @return array
     */
    protected function getSearchData()
    {
        $result = array();
        switch ($this->paramSearch->getRouteSearch()){
            case "Обязательства":
                $result =  $this->em->getRepository(ReestrbranchOut::class)->searchDataFromParam($this->paramSearch);
                break;
            case "Кредит":
                $result =  $this->em->getRepository(ReestrbranchIn::class)->searchDataFromParam($this->paramSearch);
                break;
        }
        return $result;
    }



}