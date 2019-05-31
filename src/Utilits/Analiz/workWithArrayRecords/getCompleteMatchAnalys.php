<?php


namespace App\Utilits\Analiz\workWithArrayRecords;

/**
 * Проведение анализа на полное совпадение данных
 *  -   построение сводной таблицы ["совпавшие ключи", "ПДВ по ЕРПН", "ПДВ по реестру"]
 *  -   фильтр сводной таблицы на исключение совпадения  "ПДВ по ЕРПН" == "ПДВ по реестру". Должны остатся только записи в сводной таблице, при которых "ПДВ по ЕРПН" != "ПДВ по реестру"
 *  -   формирование, на основании отфильтрованной сводной таблицы, данных для занесения в экселевскую таблицу (экспорт)
 *  -   на основании ключа "совпавшие ключи" получения данных для формирования данных для экспорта
 *      -   из ЕРПН
 *      -   из Реестров
 *
 * Использование
 *  $erpn = new workWithArrayRecordsErpn($this->em->getRepository(ErpnIn::class)->getArrayRecordsForAnaliz(3,2017));
 *  $reestr = new workWithArrayRecordsReestr($this->em->getRepository(ReestrbranchIn::class)->getArrayRecordsForAnaliz(3,2017));
 *
 *  $analys = new getCompleteMatchAnalys($erpn, $reestr);
 *      $pivotTable = $analys->getArrayPivotTableForExport();
 *          %docErpn = $analys->getArrayDocumentsByErpnForExport();
 *              $docReestr = $analys->getArrayDocumentsByReestrForExport();
 * Class getCompleteMatchAnalys
 * @package App\Utilits\Analiz\workWithArrayRecords
 *
 */
class getCompleteMatchAnalys
{
    /**
     * @var workWithArrayRecordsErpn
     */
    private $erpn;
    /**
     * @var workWithArrayRecordsReestr
     */
    private $reestr;

    /**
     * @var array сводная таблица ["совпавшие ключи", "ПДВ по ЕРПН", "ПДВ по реестру"]
     */
    protected $diffPivotTable;

    /**
     * @var array массив уникальных ключей
     */
    protected $arrayUniqKeyPivoTable;

    /**
     * getCompleteMatchAnalys constructor.
     * @param workWithArrayRecordsErpn $erpn
     * @param workWithArrayRecordsReestr $reestr
     */
    public function __construct(workWithArrayRecordsErpn $erpn, workWithArrayRecordsReestr $reestr)
    {
        $this->erpn = $erpn;
        $this->reestr = $reestr;
        $this->diffPivotTable = array();
        $this->arrayUniqKeyPivoTable = array();
        $this->buldDiffPivotTable();
    }

    /**
     * - построение сводной таблицы ["совпавшие ключи", "ПДВ по ЕРПН", "ПДВ по реестру"], где "совпавшие ключи" это ключи которые есть и в ЕРПН и в Реестре
     * - фильтр сводной таблицы на исключение совпадения  "ПДВ по ЕРПН" == "ПДВ по реестру". Должны остатся только записи в сводной таблице, при которых "ПДВ по ЕРПН" != "ПДВ по реестру"
     * - формирование, на основании отфильтрованной сводной таблицы, данных для занесения в экселевскую таблицу (экспорт)
     * @return array
     */
    private function buldDiffPivotTable():array {
        $pivotTableErpn = $this->erpn->getPivotTableMonthYearInnTypePdv();
        $pivotTableReestr = $this->reestr->getPivotTableMonthYearInnTypePdv();

        return $this->buildPivotTable($pivotTableErpn,$pivotTableReestr);


    }

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
      -   $this->diffPivotTable[$cnt]["PDV_Erpn"] ;
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
            foreach ($erpn as $key => $value) {
                if (key_exists($key, $restr)) {
                    if ($value != $restr[$key]) {
                        list($m, $y, $inn, $type) = explode("/", $key);
                        $this->arrayUniqKeyPivoTable[] = $key;
                        $this->diffPivotTable[$cnt]["month"] = $m;
                        $this->diffPivotTable[$cnt]["year"] = $y;
                        $this->diffPivotTable[$cnt]["inn"] = $inn;
                        $this->diffPivotTable[$cnt]["type"] = $type;
                        $this->diffPivotTable[$cnt]["PDV_Erpn"] = $value;
                        $this->diffPivotTable[$cnt]["PDV_Reestr"] = $restr[$key];
                        unset($m, $y, $inn, $type);
                        $cnt++;
                    }
                }
            }
    }
            return $this->diffPivotTable;
    }

    /**
     * возвращение массива строк, который можно, например, "выгружать" в таблицу
     * @return array
     */
    public function getArrayPivotTableForExport():array{
        //return $this->buldDiffPivotTable();
        return $this->diffPivotTable;
    }

    /**
     * Возвращает уникальные ключи сводной таблицы для дальнейшего использования в подборе документов
     * которые сформировали расходжения, отображенные в сводной таблице
     * @return array
     */
    public function getUniqKeyForBuildListDocuments():array {
        return $this->arrayUniqKeyPivoTable;
    }

    /**
     * Возвращение массива строк - списка документов из Erpn, который можно, например, "выгружать" в таблицу
     * @return array
     */
    public function getArrayDocumentsByErpnForExport():array {
        return $this->erpn->getDocumentsByKeys($this->arrayUniqKeyPivoTable);
    }

    /**
     * Возвращение массива строк - списка документов из Reestr, который можно, например, "выгружать" в таблицу
     * @return array
     */
    public function getArrayDocumentsByReestrForExport():array {
        return $this->reestr->getDocumentsByKeys($this->arrayUniqKeyPivoTable);
    }

}