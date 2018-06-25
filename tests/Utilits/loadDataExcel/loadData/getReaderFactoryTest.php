<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25.06.2018
 * Time: 21:20
 */

namespace Utilits\loadDataExcel\loadData;

use App\Utilits\loadDataExcel\configLoader\configLoadReestrIn;
use App\Utilits\loadDataExcel\createReaderFile\getReaderExcel;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use App\Utilits\loadDataExcel\loadData\getReaderFactory;
use App\Utilits\loadDataExcel\loadData\readDataFromRowsXls;
use App\Utilits\loadDataExcel\loadData\readDataFromRowsXlsx;
use Box\Spout\Reader\XLSX\Reader;
use PHPUnit\Framework\TestCase;

class getReaderFactoryTest extends TestCase
{
    public function dataFromCreateReaderError(){
        return[
            ["hgldfdl.txt"],
            ["0077.96969"]
        ];
    }


    /**
     * @param string $fileName
     * @throws errorLoadDataException
     * @dataProvider dataFromCreateReaderError
     */
    public function testCreateReaderError1(string $fileName){
        $this->expectException(errorLoadDataException::class);
            $this->expectExceptionMessage("Расширение файла не поддерживается!");
             getReaderFactory::createReader($fileName, new configLoadReestrIn());
    }


    public function dataFromCreateReaderError2(){
        return[
            ["hgldfdl.xls"],
            ["__DIR__.\"\\testDataReestrOut_TAB2.xlsx"]
        ];
    }

    /**
     * @param string $fileName
     * @throws errorLoadDataException
     * @dataProvider dataFromCreateReaderError2
     */
    public function testCreateReaderError2(string $fileName){
        $this->expectException(errorLoadDataException::class);
        $this->expectExceptionMessage("Класс конфигуратора отсутствует!");
        $obj = getReaderFactory::createReader($fileName,null);
    }

    public function testCreateReaderXLS(){
        $obj = getReaderFactory::createReader("hgldfdl.xls", new configLoadReestrIn());
        $this->assertInstanceOf(getReaderExcel::class,$obj);
    }

    public function testCreateReaderXLSX(){
        $obj = getReaderFactory::createReader(__DIR__."\\testDataReestrOut_TAB2.xlsx", new configLoadReestrIn());
        $this->assertInstanceOf(Reader::class,$obj);
    }
}
