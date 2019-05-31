<?php


namespace App\Utilits\Analiz\workWithArrayRecords;

/**
 * Класс, который реализует общую логику для работы с массивовом записей
 * Class workWithArrayRecordsAbstract
 * @package App\Utilits\Analiz\workWithArrayRecords
 */

abstract class workWithArrayRecordsAbstract
{
    /**
     * @var array с результатом запроса
     *  [num_invoice] => 2//638
        [date_create_invoice] => 2017-03-07
        [type_invoice_full] => ПНЕ
        [inn_client] => 203387405140
        [name_client] => ТОВ "Мегатекс"
        [suma_invoice] => 4392.62
        [pdvinvoice] => 0.00
        [baza_invoice] => 4392.62
        [name_vendor] => ПУБЛІЧНЕ АКЦІОНЕРНЕ ТОВАРИСТВО "УКРАЇНСЬКА ЗАЛІЗНИЦЯ" , Філія "Центр з ремонту та експлуатації колійних машин" 638
        [num_branch_vendor] => 638
        [key_field] => 2//638/ПНЕ/07-03-2017/203387405140
        [month_create_invoice] =>
        [year_create_invoice] =>
     *
     */
    protected $records = array();

    public function __construct(array $recordsWithRepository)
    {
        $this->records = $recordsWithRepository;
    }

    /**
     * Построение сводной таблицы вида ключ = значение
     *  ключ = месяц/год/ИНН/Тип_документа
     *  значение = сумма ПДВ по этому ключу
     * @return array
     * @throws \Exception
     */
    private function buildPivotTableMonthYearInnTypePdv():array {
        $pivotTable = array();
        foreach ($this->records as $num =>&$record){
                $dateCreate = new \DateTime($record['date_create_invoice']);
                $m = $dateCreate->format('m');
                $y = $dateCreate->format('Y');
                $key = $m.'/'.$y.'/'.$record['inn_client'].'/'.$record['type_invoice_full'];
                // создадим поле для использования в процессе получения отобранных данных (для усткорения сомого отбора)
                $record['key']=$key;
                if (key_exists($key,$pivotTable)){
                        $temp = $pivotTable[$key];
                    $pivotTable[$key]=$record['pdvinvoice']+$temp;
                }else{
                    $pivotTable[$key]=$record['pdvinvoice']+0;
                }
        }
        unset($record);
        return $pivotTable;
    }


    public function getPivotTableMonthYearInnTypePdv():array {
        return $this->buildPivotTableMonthYearInnTypePdv();
    }
}