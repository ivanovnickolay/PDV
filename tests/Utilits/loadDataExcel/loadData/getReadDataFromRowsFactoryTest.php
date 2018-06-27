<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26.06.2018
 * Time: 11:43
 */

namespace Utilits\loadDataExcel\loadData;

use App\Utilits\loadDataExcel\configLoader\configLoadReestrIn;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use App\Utilits\loadDataExcel\loadData\getReadDataFromRowsFactory;
use App\Utilits\loadDataExcel\loadData\readDataFromRowsAbstract;
use App\Utilits\loadDataExcel\loadData\readDataFromRowsXls;
use App\Utilits\loadDataExcel\loadData\readDataFromRowsXlsx;
use PHPUnit\Framework\TestCase;

class getReadDataFromRowsFactoryTest extends TestCase
{
    public function dataCreate(){
        return[
            [__DIR__."\\testDataReestrIn_TAB1.xls",readDataFromRowsXls::class],
            [__DIR__."\\testDataReestrOut_TAB2.xlsx",readDataFromRowsXlsx::class]
        ];
    }

    /**
     * Тестирование фабрики на создание класс, который будет реализовывать алгоритм построчного чтения
     * и обработки данных из файла в зависимости от расширения файла
     *  конфигуратор configLoadReestrIn() использован в тесте только для полноты параметров фукнции при создании
     * @param $file string
     * @param $obj readDataFromRowsAbstract
     * @dataProvider dataCreate
     */
    public function testCreate($file, $obj){
        $this->assertInstanceOf($obj,
        getReadDataFromRowsFactory::create($file,new configLoadReestrIn()));

    }

    public function dataCreateErrorExtension(){
        return[
            ["testDataReestrIn_TAB1.doc"],
            ["testDataReestrOut_TAB2.pdf"]
        ];
    }

    /**
     * Тестирование фабрики на бросания исключения при не поддерживаемом типе файла
     *  конфигуратор configLoadReestrIn() использован в тесте только для полноты параметров фукнции при создании
     * @param $file string
     * @param $obj readDataFromRowsAbstract
     * @dataProvider dataCreateErrorExtension
     */
    public function testCreateErrorExtension($file){
        $this->expectException(errorLoadDataException::class);
            $this->expectExceptionMessage("Расширение файла не поддерживается!");
                $obj = getReadDataFromRowsFactory::create($file,new configLoadReestrIn());

    }
}
