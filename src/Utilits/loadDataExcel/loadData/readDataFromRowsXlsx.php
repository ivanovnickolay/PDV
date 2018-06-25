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
 * Class readDataFromRowsXlsx Реализует чтение данных из строк файлов с расширенеим "*.xlsx"
 * @package App\Utilits\loadDataExcel\loadData
 */
class readDataFromRowsXlsx extends readDataFromRowsAbstract
{

    /**
     * @param handlerRowAbstract $handler
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     */
    public function readRows(handlerRowAbstract $handler)
    {
        $counter = 0;
        $maxRowForSaving = $this->configLoader->getMaxReadRow();
        $handlerRow  = $handler;
        foreach ($this->readerFile->getSheetIterator() as $sheet) {
            // only read data from 3rd sheet
            if ($sheet->getIndex() === 0) { // index is 0-based
                foreach ($sheet->getRowIterator() as $row) {
                    $counter++;
                    // Читаем строку из файла и возвращаем данные как массив
                    $arrayRow[0] = $row;
                    //передаем в массив в обработчик
                    $handler->handlerRow($arrayRow);
                    if(0==($counter/$maxRowForSaving)-round($counter/$maxRowForSaving)){
                        $handlerRow->saveHandlingRows();
                    }
                }
                // выполняем операции после обработки части строк
                $handlerRow->saveHandlingRows();

                break; // no need to read more sheets
            }
        }
        $this->readerFile->close();
    }
}