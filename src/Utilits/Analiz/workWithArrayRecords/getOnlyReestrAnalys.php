<?php


namespace App\Utilits\Analiz\workWithArrayRecords;

/**
 *  Проведение анализа на наявность данных, которые есть только в Реестре
 *
 * * Использование
 *  $erpn = new workWithArrayRecordsErpn($this->em->getRepository(ErpnIn::class)->getArrayRecordsForAnaliz(3,2017));
 *  $reestr = new workWithArrayRecordsReestr($this->em->getRepository(ReestrbranchIn::class)->getArrayRecordsForAnaliz(3,2017));
 *
 *  $analys = new getOnlyReestrAnalys($erpn, $reestr);
 *      $pivotTable = $analys->getArrayPivotTableForExport();
 *          $docReestr = $analys->getArrayDocumentsByReestrForExport();
 *
 * Class getOnlyErpnAnalys
 * @package App\Utilits\Analiz\workWithArrayRecords
 *
 */
class getOnlyReestrAnalys extends getCompleteMatchAnalys
{

    /**
     * @var array сводная таблица ["ключи, которые есть только в Реестре", "ПДВ по Реестру"]
     */
    protected $diffPivotTable;

    /**
     * Построение сводной таблицы
     *  при вызове происходит контроль заполнения этого массива - он заполняется только один раз.
     *   При последующих вызовах
     *  он возвращается как заполненный ранее
     *
     * структура массива, который возвращается
    -   $this->diffPivotTable[$cnt]["month"];
    -   $this->diffPivotTable[$cnt]["year"] ;
    -   $this->diffPivotTable[$cnt]["inn"] ;
    -   $this->diffPivotTable[$cnt]["type"] ;
    -   $this->diffPivotTable[$cnt]["PDV_Reestr"] ;
    *
     * @param array $erpn
     * @param array $restr
     * @return  array
     */
    private function buildPivotTable(array $erpn, array $restr):array
    {
        $cnt = 0;
        if (empty($this->diffPivotTable)){
            foreach ($restr as $key => $value) {
                if (!key_exists($key, $erpn)) {
                   list($m, $y, $inn, $type) = explode("/", $key);
                        $this->arrayUniqKeyPivoTable[] = $key;
                        $this->diffPivotTable[$cnt]["month"] = $m;
                        $this->diffPivotTable[$cnt]["year"] = $y;
                        $this->diffPivotTable[$cnt]["inn"] = $inn;
                        $this->diffPivotTable[$cnt]["type"] = $type;
                        $this->diffPivotTable[$cnt]["PDV_Reestr"] = $value;
                        unset($m, $y, $inn, $type);
                        $cnt++;
                }
            }
        }
        return $this->diffPivotTable;
    }


    /**
     * так проводится анализ на получение документов только из Реестров, документы из ЕРПН не можнно получить
     * для безопастности - при попытке получить документы из ЕРПН выводим пустой массив
     * @return array
     */
    public function getArrayDocumentsByErpnForExport():array {
        return array();
    }

}