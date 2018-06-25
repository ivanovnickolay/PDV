<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 20.06.2018
 * Time: 16:09
 */

namespace App\Utilits\loadDataExcel\cacheDataRow;

use App\Utilits\loadDataExcel\configLoader\configLoadReestrIn;
use App\Utilits\loadDataExcel\createEntityForLoad\entityForLoad\createReestrIn;
use App\Utilits\loadDataExcel\handlerRow\handlerRowsValid;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class cacheDataRowTest extends TestCase
{

    public function setUp(){


    }

    public function testAddData_NoObject()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Для добавления в кеш передан не объект");
        $test = "fsdlhflshf";
        $obj  = new cacheDataRow();
            $obj->addData($test);
    }

    public function testAddData_NoValidObject()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Для добавления в кеш передан не верный объект");
        $test = new configLoadReestrIn();
        $obj  = new cacheDataRow();
        $obj->addData($test);
    }

    public function testAddData_Object()
    {
        $test = new configLoadReestrIn();
        $obj  = new cacheDataRow();
        $obj->addData($this->getReestrIn());
        $arr = $obj->getArrayCache();
        $this->assertNotEmpty($arr);
    }

    private function getArrayData(): array
    {
        $arr = array();
        //$this->reestrIn=new ReestrbranchIn();
        //$this->reestrIn->setMonth($arr[0][79]);
        $arr[0][79] = 8;
        //$this->reestrIn->setYear($arr[0][87]);
        $arr[0][87] = 2016;
        //$this->reestrIn->setNumBranch($arr[0][66]);
        $arr[0][66] = '578';
        //$this->reestrIn->setDateGetInvoice(//$this->getDataType($arr[0][105]));
        //$arr[0][105]=new \DateTime("2017-01-01 00:00:00",new \DateTimeZone('Europe/Kiev'));
        $arr[0][105] = "42564";
        //$this->reestrIn->setDateCreateInvoice(//$this->getDataType($arr[0][126]));
        $arr[0][126] = "42552";
        //$this->reestrIn->setNumInvoice($arr[0][106]);
        $arr[0][106] = '15';
        //$this->reestrIn->setTypeInvoiceFull($arr[0][134]);
        $arr[0][134] = "ПНЕ";
        //$this->reestrIn->setNameClient($arr[0][108]);
        $arr[0][108] = "Рога и копыта";
        //$this->reestrIn->setInnClient($arr[0][109]);
        $arr[0][109] = "100000000000";
        //$this->reestrIn->setZagSumm($arr[0][111]);
        $arr[0][111] = '12124.22';
        //$this->reestrIn->setBaza20($arr[0][113]);
        $arr[0][113] = "1212.11";
        //$this->reestrIn->setPdv20($arr[0][116]);
        $arr[0][116] = "1213.22";
        //$this->reestrIn->setBaza7($arr[0][114]);
        $arr[0][114] = 0;
        //$this->reestrIn->setPdv7($arr[0][117]);
        $arr[0][117] = 0;
        //$this->reestrIn->setBaza0($arr[0][115]);
        $arr[0][115] = 0;
        //$this->reestrIn->setPdv0($arr[0][118]);
        $arr[0][118] = 0;
        //$this->reestrIn->setBazaZvil($arr[0][120]);
        $arr[0][120] = 0;
        //$this->reestrIn->setPdvZvil($arr[0][95]);
        $arr[0][95] = 0;
        //$this->reestrIn->setBazaNeGos($arr[0][98]);
        $arr[0][98] = 0;
        //$this->reestrIn->setPdvNeGos($arr[0][101]);
        $arr[0][101] = 0;
        //$this->reestrIn->setBazaZaMezhi($arr[0][103]);
        $arr[0][103] = 0;
        //$this->reestrIn->setPdvZaMezhi($arr[0][104]);
        $arr[0][104] = 0;
        //$this->reestrIn->setRkeDateCreateInvoice($this->getDataType($arr[0][122]));
        $arr[0][122] = '';
        //$this->reestrIn->setRkeNumInvoice($arr[0][123]);
        $arr[0][123] = '';
        //$this->reestrIn->setRkePidstava($arr[0][124]);
        $arr[0][124] = '';
        //$this->reestrIn->setKeyField();
        return $arr;

    }

    private function getReestrIn(){
        $ff =  new createReestrIn();
        return $ff->createReestr($this->getArrayData());
    }

    /**
     * проверка работы кеширования в методе setRowToCache класса handlerRowsValid
     * @throws \ReflectionException
     */
    public function test_handleRowValid(){
        // делаем частный метод публичным в рамках теста
        $method = new \ReflectionMethod(handlerRowsValid::class,"setRowToCache");
        $method->setAccessible(true);
        // создаем  объект для тестирования
        $objectManager = $this->createMock(EntityManager::class);
        $configLoader = $this->createMock(configLoadReestrIn::class);
        $obj = new handlerRowsValid($objectManager,$configLoader);
        // объект кеша
        $cache = new cacheDataRow();
        // установим кеш
        $obj->setCache($cache);
        $method->invoke($obj,$this->getReestrIn());
        // проверим наличие записи данных в кеше
        $ar = $cache->getArrayCache();
        $this->assertNotEmpty($ar);
        // востановим объект
        $tempReestr = unserialize($ar[0]);
        // проверим идентичность
        $this->assertEquals($this->getReestrIn(),$tempReestr);

    }
}
