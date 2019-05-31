<?php


namespace App\Utilits\Analiz\workWithArrayRecords;

/**
 * Класс работы с данными полученными из ЕРПН
 * Class workWithArrayRecordsErpn
 * @package App\Utilits\Analiz\workWithArrayRecords
 */
class workWithArrayRecordsErpn extends workWithArrayRecordsAbstract
{
    public function __construct(array $recordsWithRepository)
    {
        parent::__construct($recordsWithRepository);
    }
    /**
     * Формирование массива документов для выгрузки
     * @param array $arrayUniqKeys
     * @return array
     */
    public function getDocumentsByKeys(array $arrayUniqKeys){

        if (!empty($arrayUniqKeys)){
            $arrayResult = array();
            $cnt = 0;
            foreach ($this->records as $num =>$record){
                if (in_array($record['key'],$arrayUniqKeys)){
                    $arrayResult[$cnt]['num_invoice']=$record['num_invoice'];
                    $arrayResult[$cnt]['date_create_invoice']=$record['date_create_invoice'];
                    $arrayResult[$cnt]['type_invoice_full']=$record['type_invoice_full'];
                    $arrayResult[$cnt]['inn_client']=$record['inn_client'];
                    $arrayResult[$cnt]['name_client']=$record['name_client'];
                    $arrayResult[$cnt]['suma_invoice']=$record['suma_invoice'];
                    $arrayResult[$cnt]['pdvinvoice']=$record['pdvinvoice'];
                    $arrayResult[$cnt]['baza_invoice']=$record['baza_invoice'];
                    $arrayResult[$cnt]['name_vendor']=$record['name_vendor'];
                    $arrayResult[$cnt]['num_branch_vendor']=$record['num_branch_vendor'];
                    $cnt++;
                }
            }
            return $arrayResult;
        }
    }

}