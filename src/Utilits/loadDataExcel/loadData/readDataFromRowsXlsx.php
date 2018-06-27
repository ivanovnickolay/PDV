<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25.06.2018
 * Time: 21:38
 */

namespace App\Utilits\loadDataExcel\loadData;

use App\Utilits\loadDataExcel\configLoader\configLoaderFactory;
use App\Utilits\loadDataExcel\handlerRow\handlerRowAbstract;
use App\Utilits\loadDataExcel\loadData\readDataFromRowsAbstract;



/**
 * Class readDataFromRowsXlsx Реализует чтение данных из строк файлов с расширенеим "*.xlsx"
 * ЗАМЕНА класса loadData
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
        $counter = 1;
        $maxRowForSaving = $this->configLoader->getMaxReadRow();
        $handlerRow  = $handler;
        foreach ($this->readerFile->getSheetIterator() as $sheet) {
            // only read data from 3rd sheet
            if ($sheet->getIndex() === 0) { // index is 0-based
                foreach ($sheet->getRowIterator() as $row) {
                    // первая строка - строка заголовков. ее не обрабатываем
                    if ($counter == 1){
                        $counter++;
                        continue;
                    }
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

    /**
     * чтение из файла конкретной строки данных
     *  АККУРАТНО - использовать с умом - идет перебор всех (!) строк файла
     * @param int $rowNumber номер строки данные из которой надо вернуть
     * @return array массив данных с прочитанной строки
     */
    public function readRowNumber(int $rowNumber):array {
        $countRow=1;
        $arr = array();
        // читаем вторую строку с данными (нумерация строк с нуля)
        foreach ($this->readerFile->getSheetIterator() as $sheet){
            foreach ($sheet->getRowIterator() as $row){
                if ($countRow==$rowNumber){
                    $arr[]=$row;
                    break;
                }
                $countRow++;
            }
            break;
        }
        return $arr;
    }
}