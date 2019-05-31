<?php

namespace App\Utilits\Analiz\workWithArrayRecords;

use App\Entity\ErpnOut;
use App\Entity\ReestrbranchIn;
use App\Entity\ReestrbranchOut;
use App\Services\LoadReestrFromFile;
use App\Utilits\loadDataExcel\cacheDataRow\cacheDataRow;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use App\Utilits\workToFileSystem\workWithFiles;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class workWithArrayRecordsReestrTest extends KernelTestCase
{
    /**
     * @var ObjectManager|object
     */
    private $em;

    /**
     * @var string
     */
    private $dirFixturesFiles;

    /**
     * @var string
     */
    private $dirForMoveFilesWithError;

    /**
     * @var string
     */
    private $dirForMoveFiles;

    /**
     * @var string
     */
    private $toFiles;

    /**
     * @throws errorLoadDataException
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

       $this->dirFixturesFiles = __DIR__."//Fixtures//fixturesFiles";
       $this->toFiles = __DIR__."//Fixtures//dirForLoadFiles";
       $this->dirForMoveFilesWithError = __DIR__."//Fixtures//dirForMoveFilesWithError";
       $this->dirForMoveFiles=__DIR__."//Fixtures//dirForMoveFiles";
       workWithFiles::moveFiles(
           $this->dirFixturesFiles."//testDataСorrectReestrIn_TAB1.xls" ,
           $this->toFiles
       );
       workWithFiles::moveFiles(
           $this->dirFixturesFiles."//testDataСorrectReestrOut_TAB2.xls" ,
           $this->toFiles
       );

       $load = new LoadReestrFromFile($this->em);
        $load->setDirForLoadFiles($this->toFiles);
            $load->setDirForMoveFiles($this->dirForMoveFiles);
                $load->setDirForMoveFilesWithError($this->dirForMoveFilesWithError);
                    $load->setCache(new cacheDataRow());
                        $load->execute();
        $this->validExecute();

   }

    private function validExecute(){
        $this->assertFileNotExists($this->dirForMoveFilesWithError."\\testDataСorrectReestrIn_TAB1.xls");
        $this->assertFileExists($this->dirForMoveFiles."\\testDataСorrectReestrIn_TAB1.xls");
        // Проведем проверку что загрузилось
        // контроль количества записей
        $SQLCountRec = "SELECT COUNT(id) FROM reestrbranch_in";
        $smtpCountRec = $this->em->getConnection()->prepare($SQLCountRec);
        $smtpCountRec->execute();
        $arrayResult = $smtpCountRec->fetchAll();
        $this->assertEquals(7, $arrayResult[0]['COUNT(id)']);
    }

    public function tearDown():void {
        // очистим таблицы с данными
        $SQLDeleteRecIn = "DELETE  FROM reestrbranch_in";
        $smtpDeleteRecIn = $this->em->getConnection()->prepare($SQLDeleteRecIn);
        $smtpDeleteRecIn->execute();

        $SQLDeleteRecOut = "DELETE  FROM reestrbranch_out";
        $smtpDeleteRecOut = $this->em->getConnection()->prepare($SQLDeleteRecOut);
        $smtpDeleteRecOut->execute();

        workWithFiles::moveFiles(
            $this->dirForMoveFiles."//testDataСorrectReestrIn_TAB1.xls",
            $this->dirFixturesFiles

        );
        workWithFiles::moveFiles(
            $this->dirForMoveFiles."//testDataСorrectReestrOut_TAB2.xls" ,
            $this->dirFixturesFiles
        );
    }

    public function testCreateClass (){
        $obj = new workWithArrayRecordsReestr(
            $this->em->getRepository(ReestrbranchIn::class)->getArrayRecordsForAnaliz(3,2017)
        );
        $this->assertInstanceOf(workWithArrayRecordsReestr::class,$obj);
    }

    /**
     * Тестироривание построение сводной таблицы на основе данных ErpnOut
     */
    public function testCreatePivotTableReestrOut (){
        $obj = new workWithArrayRecordsReestr(
            $this->em->getRepository(ReestrbranchOut::class)->getArrayRecordsForAnaliz(12,2016)
        );
        $arr = $obj->getPivotTableMonthYearInnTypePdv();

        $this->assertEquals(count($arr),3);

        $this->assertTrue(key_exists('12/2016/100000000000/ПНЕ',$arr));
        $this->assertEquals($arr['12/2016/100000000000/ПНЕ'],221.18);

        $this->assertTrue(key_exists('12/2016/400000000000/РКЕ',$arr));
        $this->assertEquals($arr['12/2016/400000000000/РКЕ'],-399.82);

        $this->assertTrue(key_exists('12/2016/600000000000/ПНЕ',$arr));
        $this->assertEquals($arr['12/2016/600000000000/ПНЕ'],5215.0);
    }
    /**
     * Тестирование метода getDocumentsByKeys на формирование выборки документов из ReestrOut
     * согластно переданного массива уникальных ключей
     */
    public function testGetDocumentsByKeysReestrOut()
    {
        $obj = new workWithArrayRecordsReestr(
            $this->em->getRepository(ReestrbranchOut::class)->getArrayRecordsForAnaliz(12,2016)
        );
        $arr = $obj->getPivotTableMonthYearInnTypePdv();
        $arrResult = $obj->getDocumentsByKeys(['12/2016/100000000000/ПНЕ','12/2016/400000000000/РКЕ']);

        $this->assertEquals(count($arrResult),5);
        // Проверим количество элементов в одной записи массива
        $this->assertEquals(count($arrResult[0]),11);

        // Проверим что бы действительно передались данные по выбранным ключам
        $this->assertEquals($arrResult[0]['date_create_invoice'],'2016-12-29');
        $this->assertEquals($arrResult[0]['type_invoice_full'],'ПНЕ');
        $this->assertEquals($arrResult[0]['inn_client'],'100000000000');

        $this->assertEquals($arrResult[2]['date_create_invoice'],'2016-12-31');
        $this->assertEquals($arrResult[2]['type_invoice_full'],'РКЕ');
        $this->assertEquals($arrResult[2]['inn_client'],'400000000000');
    }


    /**
     * Тестирование построение сводной таблицы на основе данных ErpnOut
     */
    public function testCreatePivotTableReestrIn (){
        $obj = new workWithArrayRecordsReestr(
            $this->em->getRepository(ReestrbranchIn::class)->getArrayRecordsForAnaliz(12,2016)
        );
        $arr = $obj->getPivotTableMonthYearInnTypePdv();

        $this->assertEquals(count($arr),5);

        $this->assertTrue(key_exists('04/2016/215607626656/ПНЕ',$arr));
        $this->assertEquals($arr['04/2016/215607626656/ПНЕ'],57.3);

        $this->assertTrue(key_exists('12/2016/400758126555/ТК',$arr));
        $this->assertEquals($arr['12/2016/400758126555/ТК'],55.67);

        $this->assertTrue(key_exists('11/2016/364958904638/РКЕ',$arr));
        $this->assertEquals($arr['11/2016/364958904638/РКЕ'],0.0);

        $this->assertTrue(key_exists('12/2016/380922526569/РКЕ',$arr));
        $this->assertEquals($arr['12/2016/380922526569/РКЕ'],60.0);

        $this->assertTrue(key_exists('11/2016/232263611232/ПО',$arr));
        $this->assertEquals($arr['11/2016/232263611232/ПО'],20246.94);
    }

    /**
     * Тестирование метода getDocumentsByKeys на формирование выборки документов из ReestrOut
     * согластно переданного массива уникальных ключей
     */
    public function testGetDocumentsByKeysReestrIn()
    {
        $obj = new workWithArrayRecordsReestr(
            $this->em->getRepository(ReestrbranchIn::class)->getArrayRecordsForAnaliz(12,2016)
        );

        $arr = $obj->getPivotTableMonthYearInnTypePdv();
        $arrResult = $obj->getDocumentsByKeys(['04/2016/215607626656/ПНЕ','12/2016/400758126555/ТК']);

        $this->assertEquals(count($arrResult),4);
        // Проверим количество элементов в одной записи массива
        $this->assertEquals(count($arrResult[0]),11);

        // Проверим что бы действительно передались данные по выбранным ключам
        $this->assertEquals($arrResult[0]['date_create_invoice'],'2016-04-30');
        $this->assertEquals($arrResult[0]['type_invoice_full'],'ПНЕ');
        $this->assertEquals($arrResult[0]['inn_client'],'215607626656');

        $this->assertEquals($arrResult[1]['date_create_invoice'],'2016-12-05');
        $this->assertEquals($arrResult[1]['type_invoice_full'],'ТК');
        $this->assertEquals($arrResult[1]['inn_client'],'400758126555');
    }

}
