<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.02.2018
 * Time: 15:53
 */

namespace App\Utilits\loadDataExcel\loadData;

use App\Utilits\loadDataExcel\configLoader\configLoaderFactory;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use App\Utilits\loadDataExcel\handlerRow\handlerRowsValid;
use PHPUnit\Framework\TestCase;

class readDataFromRowsXlsxTest extends TestCase
{

    /**
     * Тестирование создания класса чтения обязательств
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     */
    public function test__constructOut()
    {
        //$fileName = 'C:\OSPanel\domains\PDV_UZ\tests\Utilits\loadDataExcel\createEntityForLoad\entityForLoad\testDataReestrOut_TAB2.xlsx';
        $fileName=__DIR__."//testDataReestrOut_TAB2.xlsx";
        $load  = new readDataFromRowsXlsx( $fileName,
            configLoaderFactory::getConfigLoad($fileName)
        );
        $this->assertInstanceOf(readDataFromRowsXlsx::class,$load);
    }

    /**
     * тестирование возникновения ошибки при отсутствии конфигуратора загрузки
     * @throws errorLoadDataException
     */
    public function test__construct_Error()
    {
        //$fileName = 'C:\OSPanel\domains\PDV_UZ\tests\Utilits\loadDataExcel\createEntityForLoad\entityForLoad\testDataReestrOut_TAB2.xlsx';
        $fileName=__DIR__."//testDataReestrOut_TAB4.xlsx";
        $this->expectException(errorLoadDataException::class);
        $this->expectExceptionMessage("Для файла ".$fileName." не существует конфигурации для чтения информации из файла !");

        $load  = new readDataFromRowsXlsx( $fileName,
            configLoaderFactory::getConfigLoad($fileName)
        );
        $this->assertInstanceOf(readDataFromRowsXlsx::class,$load);
    }

}
