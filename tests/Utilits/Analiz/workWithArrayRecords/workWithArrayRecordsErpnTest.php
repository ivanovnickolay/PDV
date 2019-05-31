<?php

namespace App\Utilits\Analiz\workWithArrayRecords;

use App\Entity\ErpnIn;
use App\Entity\ErpnOut;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Driver\Connection;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\{KernelTestCase};

class workWithArrayRecordsErpnTest extends KernelTestCase
{

    /**
     * @var ObjectManager|object
     */
     private $em;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        // получаем Entity Manager
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $rr=$this->em->getConnection()->getDatabase();
        if ("AnalizPDV_test"!=$rr){
            throw new Exception();
        }
        // загрузка тестовых данных
        $this->loadData();

    }
    /**
     * загрузка тестовых данных
     */
    private function loadData(){
        $fileNameIn  = __DIR__."//Fixtures//template_CSV_In(03_2017do_zagruzka).csv";
        $this->em->getRepository(ErpnIn::class)->loadDataInFile($fileNameIn);

        $fileNameOut  = __DIR__."//Fixtures//template_CSV_Out(03_2017do_zagruzka).csv";
        $this->em->getRepository(ErpnOut::class)->loadDataInFile($fileNameOut);
    }

    public function tearDown():void
    {
        $this->clearDB();
    }

    /**
     * удаление тестовых данных из базы данных
     */
    private function clearDB(){
        $SQLDeleteRecIn = "DELETE  FROM erpn_in";
        /** @var Connection $smtpDeleteRecIn */
        $smtpDeleteRecIn = $this->em->getConnection()->prepare($SQLDeleteRecIn);
        $smtpDeleteRecIn->execute();

        $SQLDeleteRecOut = "DELETE  FROM erpn_out";
        $smtpDeleteRecOut = $this->em->getConnection()->prepare($SQLDeleteRecOut);
        $smtpDeleteRecOut->execute();
    }

    /**
     * Тестирование на создание объекта класса
     */
    public function testCreateClass (){
        $obj = new workWithArrayRecordsErpn(
            $this->em->getRepository(ErpnOut::class)->getArrayRecordsForAnaliz(3,2017)
        );
        $this->assertInstanceOf(workWithArrayRecordsErpn::class,$obj);
    }

    /**
     * Тестирование построение сводной таблицы на основе данных ErpnOut
     */
    public function testCreatePivotTableErpnOut (){
        $obj = new workWithArrayRecordsErpn(
            $this->em->getRepository(ErpnOut::class)->getArrayRecordsForAnaliz(3,2017)
        );
        $arr = $obj->getPivotTableMonthYearInnTypePdv();

        $this->assertEquals(count($arr),4);

        $this->assertTrue(key_exists('03/2017/009558518131/ПНЕ',$arr));
        $this->assertEquals($arr['03/2017/009558518131/ПНЕ'],347.33);

        $this->assertTrue(key_exists('03/2017/203387405140/ПНЕ',$arr));
        $this->assertEquals($arr['03/2017/203387405140/ПНЕ'],0);

        $this->assertTrue(key_exists('03/2017/306005905092/ПНЕ',$arr));
        $this->assertEquals($arr['03/2017/306005905092/ПНЕ'],13367.18);

        $this->assertTrue(key_exists('03/2017/377754316017/ПНЕ',$arr));
        $this->assertEquals($arr['03/2017/377754316017/ПНЕ'],0.00);
    }

    /**
     * Тестирование метода getDocumentsByKeys на формирование выборки документов из ErpnOut
     * согластно переданного массива уникальных ключей
     */
    public function testGetDocumentsByKeysErpnOut()
    {

        $obj = new workWithArrayRecordsErpn(
            $this->em->getRepository(ErpnOut::class)->getArrayRecordsForAnaliz(3,2017)
        );
        $arr = $obj->getPivotTableMonthYearInnTypePdv();
        $arrResult =  $obj->getDocumentsByKeys(['03/2017/009558518131/ПНЕ','03/2017/377754316017/ПНЕ']);
        $this->assertEquals(count($arrResult),2);
        // Проверим количество элементов в одной записи массива
        $this->assertEquals(count($arrResult[0]),10);
        // Проверим что бы действительно передались данные по выбранным ключам
        $this->assertEquals($arrResult[0]['date_create_invoice'],'2017-03-01');
        $this->assertEquals($arrResult[0]['type_invoice_full'],'ПНЕ');
        $this->assertEquals($arrResult[0]['inn_client'],'009558518131');

        $this->assertEquals($arrResult[1]['date_create_invoice'],'2017-03-20');
        $this->assertEquals($arrResult[1]['type_invoice_full'],'ПНЕ');
        $this->assertEquals($arrResult[1]['inn_client'],'377754316017');
    }

    /**
     * Тестирование построение сводной таблицы на основе данных ErpnOut
     */
    public function testCreatePivotTableErpnIn (){
        $obj = new workWithArrayRecordsErpn(
            $this->em->getRepository(ErpnIn::class)->getArrayRecordsForAnaliz(3,2017)
        );
        $arr = $obj->getPivotTableMonthYearInnTypePdv();

        $this->assertEquals(count($arr),37);

        $this->assertTrue(key_exists('03/2017/3819720424/РКЕ',$arr));
        $this->assertEquals($arr['03/2017/3819720424/РКЕ'],-0.01);

        $this->assertTrue(key_exists('03/2017/324773000000/РКЕ',$arr));
        $this->assertEquals($arr['03/2017/324773000000/РКЕ'],0);

        $this->assertTrue(key_exists('03/2017/345013000000/ПНЕ',$arr));
        $this->assertEquals($arr['03/2017/345013000000/ПНЕ'],23378.79);

        $this->assertTrue(key_exists('03/2017/308979000000/ПНЕ',$arr));
        $this->assertEquals($arr['03/2017/308979000000/ПНЕ'],118743.00);
    }

    /**
     * Тестирование метода getDocumentsByKeys на формирование выборки документов из ErpnIn
     * согластно переданного массива уникальных ключей
     */
    public function testGetDocumentsByKeysErpnIn()
    {

        $obj = new workWithArrayRecordsErpn(
            $this->em->getRepository(ErpnIn::class)->getArrayRecordsForAnaliz(3,2017)
        );
       $arr = $obj->getPivotTableMonthYearInnTypePdv();
       $arrResult =  $obj->getDocumentsByKeys(['03/2017/324773000000/РКЕ','03/2017/308979000000/ПНЕ']);
        $this->assertEquals(count($arrResult),2);
        // Проверим количество элементов в одной записи массива
        $this->assertEquals(count($arrResult[0]),10);
        // Проверим что бы действительно передались данные по выбранным ключам
        $this->assertEquals($arrResult[0]['date_create_invoice'],'2017-03-28');
        $this->assertEquals($arrResult[0]['type_invoice_full'],'РКЕ');
        $this->assertEquals($arrResult[0]['inn_client'],'324773000000');

        $this->assertEquals($arrResult[1]['date_create_invoice'],'2017-03-31');
        $this->assertEquals($arrResult[1]['type_invoice_full'],'ПНЕ');
        $this->assertEquals($arrResult[1]['inn_client'],'308979000000');
    }


}
