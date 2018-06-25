<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25.06.2018
 * Time: 21:38
 */

namespace App\Utilits\loadDataExcel\loadData;


use App\Utilits\loadDataExcel\handlerRow\handlerRowAbstract;
use App\Utilits\loadDataExcel\loadData\readDataFromRowsAbstract;

/**
 * Class readDataFromRowsXls Реализует чтение данных из строк файлов с расширенеим "*.Xls"
 * @package App\Utilits\loadDataExcel\loadData
 */
class readDataFromRowsXls extends readDataFromRowsAbstract
{

    public function readRows(handlerRowAbstract $handler)
    {
        $handlerRow  = $handler;
        $maxRowToFile = $this->readerFile->getMaxRow ();
        for ($startRow = 2; $startRow <= $maxRowToFile; $startRow += $this->readerFile->getFilterChunkSize())
        {
            $this->readerFile->loadDataFromFileWithFilter ($startRow);
            $maxRowReader = $this->readerFile->getFilterChunkSize () + $startRow;
            if ($maxRowReader > $maxRowToFile) {
                // специально что бы была прочитана последняя строка с данными
                $maxRowReader = $maxRowToFile + 1;
            }
            for ($d = $startRow; $d < $maxRowReader; $d ++) {
                // Читаем строку из файла и возвращаем данные как массив
                $arrayRow = $this->readerFile->getRowDataArray ($d);
                //передаем в массив в обработчик
                $handler->handlerRow($arrayRow);
            }
            // выполняем операции после обработки части строк
            $handlerRow->saveHandlingRows();
            // очищаем загрузчик данных
            $this->readerFile->unset_loadFileWithFilter ();
            //http://ru.php.net/manual/ru/features.gc.collecting-cycles.php
            gc_enable();
            gc_collect_cycles();
        }
    }
}