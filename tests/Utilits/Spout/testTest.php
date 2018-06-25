<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25.06.2018
 * Time: 16:12
 */

namespace Utilits\Spout;

use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Reader\XLSX\Reader;
use testSpout;
use PHPUnit\Framework\TestCase;

class testTest extends TestCase
{


    public function test_createSpoutReader(){
        $reader = ReaderFactory::create(Type::XLSX);
         $this->assertInstanceOf(Reader::class, $reader);
            $reader->close();
    }

    public function test_ReaderRows(){
        $countRow = 0;
        $reader = ReaderFactory::create(Type::XLSX);
            $reader->open(__DIR__."\\testDataLargeFileReestrOut_TAB2.xlsx");
                foreach ($reader->getSheetIterator() as $sheet){
                    if($sheet->getName() ==="Sheet1" ){
                        foreach ($sheet->getRowIterator() as $row){
                            $countRow++;
                        }
                    }
                    break;
                }
                $this->assertEquals(10033, $countRow);

    }

}
