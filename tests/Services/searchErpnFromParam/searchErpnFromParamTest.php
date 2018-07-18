<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 16.07.2018
 * Time: 21:55
 */

namespace Services;

use App\Entity\ErpnIn;
use App\Entity\ErpnOut;
use App\Entity\forForm\search\docFromParam;
use App\Services\searchErpnFromParam;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class searchErpnFromParamTest extends KernelTestCase
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager|object
     */
    private $em;

    /**
     * @var docFromParam
     */
    private $param;

    /**
     * searchErpnTest constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();

        // получаем Entity Manager
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $rr=$this->em->getConnection()->getDatabase();
        if ("AnalizPDV_test"!=$rr){
            throw new \Exception();
        }
    }
    /**
     * инициализация типовых параметров
     */
    private function initParam(){
        $this->param = new  docFromParam();
        $this->param->setMonthCreate(3);
        $this->param->setYearCreate(2017);
    }

    /**
     * проверка приватного метода validParamSearch - организация поиска данных в базе
     * @throws \ReflectionException
     */
    public function test_validParam(){
        $class = new \ReflectionClass(searchErpnFromParam::class);
        $method = $class->getMethod('validParamSearch');
        $method->setAccessible(true);

        $obj = new searchErpnFromParam($this->em);
        $this->initParam();
        $this->param->setINN("hlsdhfdlshf");
        $obj->setParamSearch($this->param);
        $res1 = $method->invoke($obj);
        $this->assertEquals(1,count($res1));
        $this->assertEquals("ИНН \"hlsdhfdlshf\" должен содержать только цифры .",$res1["INN"]);

        $this->param->setDateCreateDoc(new \DateTime("2018-01-01"));
        $res2 = $method->invoke($obj);
        $this->assertEquals(2,count($res2));
        $this->assertEquals("Период поиска документа и дата создания документа должны совпадать !",$res2["dateCreateDoc"]);
    }

    /**
     * загрузка тестовых данных
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \Doctrine\DBAL\DBALException
     */
    private function loadData(){
        $fileNameIn  = __DIR__."//Fixtures//template_CSV_In(03_2017do_zagruzka).csv";
        $this->em->getRepository(ErpnIn::class)->loadDataInFile($fileNameIn);

        $fileNameOut  = __DIR__."//Fixtures//template_CSV_Out(03_2017do_zagruzka).csv";
        $this->em->getRepository(ErpnOut::class)->loadDataInFile($fileNameOut);
    }

    /**
     * удаление тестовых данных из базы данных
     */
    private function clearDB(){
        $SQLDeleteRecIn = "DELETE  FROM erpn_in";
        $smtpDeleteRecIn = $this->em->getConnection()->prepare($SQLDeleteRecIn);
        $smtpDeleteRecIn->execute();

        $SQLDeleteRecOut = "DELETE  FROM erpn_out";
        $smtpDeleteRecOut = $this->em->getConnection()->prepare($SQLDeleteRecOut);
        $smtpDeleteRecOut->execute();
    }

    /**
     * Тестирование приватного метода getArraySearchData -  полученния данных поиска в виде массива значений
     * @throws \ReflectionException
     */
    public function test_getArraySearchData(){
        $this->loadData();

        $class = new \ReflectionClass(searchErpnFromParam::class);
            $method = $class->getMethod('getArraySearchData');
                $method->setAccessible(true);
        $obj = new searchErpnFromParam($this->em);
            $this->initParam();
                $this->param->setTypeDoc("ПНЕ");
        // проверка поиска обязательств
        $this->param->setRouteSearch("Обязательства");
            $obj->setParamSearch($this->param);
            $resOut = $method->invoke($obj);
                $this->assertEquals(5,count($resOut));
                    $this->assertEquals(10,count($resOut[0]));
                    $this->assertEquals("1//571",$resOut[0]["NumInvoice"]);
                    $this->assertEquals(new \DateTime("2017-03-01 00:00:00.000000"),$resOut[0]["DateCreateInvoice"]);
                    $this->assertEquals("ПНЕ",$resOut[0]["TypeInvoiceFull"]);
                    $this->assertEquals("009558518131",$resOut[0]["InnClient"]);
                    $this->assertEquals("ПРИВАТНЕ АКЦІОНЕРНЕ ТОВАРИСТВО \"БІЛОВОДСЬКИЙ КОМБІНАТ ХЛІБОПРОДУКТІВ\"",$resOut[0]["NameClient"]);
                    $this->assertEquals(2083.96,$resOut[0]["SumaInvoice"]);
                    $this->assertEquals(1736.63,$resOut[0]["BazaInvoice"]);
                    $this->assertEquals(347.33,$resOut[0]["Pdvinvoice"]);
                    $this->assertEquals("/філія \"Південна залізниця\" ПАТ \"Укрзалізниця\"/ СП \"Харківський центр професійної освіти\" філії \"Південна залізниця\" ПАТ \"Укрзалізниця\"",$resOut[0]["NameVendor"]);
                    $this->assertEquals("571",$resOut[0]["NumBranchVendor"]);

        $this->param->setRouteSearch("Кредит");
            $resIn = $method->invoke($obj);
            $this->assertEquals(49,count($resIn));
                $this->assertEquals(10,count($resIn[0]));
                    $this->assertEquals("1",$resIn[0]["NumInvoice"]);
                    $this->assertEquals(new \DateTime("2017-03-23 00:00:00.000000"),$resIn[0]["DateCreateInvoice"]);
                    $this->assertEquals("ПНЕ",$resIn[0]["TypeInvoiceFull"]);
                    $this->assertEquals("345012604630",$resIn[0]["InnClient"]);
                    $this->assertEquals("ТОВАРИСТВО З ОБМЕЖЕНОЮ ВІДПОВІДАЛЬНІСТЮ \"КОМПАНІЯ ПРОМІНСТРУМЕНТ\"",$resIn[0]["NameClient"]);
                    $this->assertEquals(140272.74,$resIn[0]["SumaInvoice"]);
                    $this->assertEquals(116893.95,$resIn[0]["BazaInvoice"]);
                    $this->assertEquals(23378.79,$resIn[0]["Pdvinvoice"]);
                    $this->assertEquals("ПУБЛІЧНЕ АКЦІОНЕРНЕ ТОВАРИСТВО \"УКРАЇНСЬКА ЗАЛІЗНИЦЯ\" РЕГІОНАЛЬНА ФІЛІЯ \"ДОНЕЦЬКА ЗАЛІЗНИЦЯ\" СТРУКТУРНИЙ ПІДРОЗДІЛ \"ДОНЕЦЬКИЙ ГОЛОВНИЙ МАТЕРІАЛЬНО-ТЕХНІЧНИЙ СКЛАД\"",$resIn[0]["NameVendor"]);
                    $this->assertEquals("779",$resIn[0]["NumBranchVendor"]);
        $this->clearDB();
    }

    /**
     * тестирование метода search - получение итоговых массивов по результатам валидации и запроса данных
     * тестирование ошибок валидации данных - про наличии ошибок поиск проиходит не должен
     */
    public function test_search_ErrorValidation(){
        $this->loadData();
            $this->initParam();
    $obj = new searchErpnFromParam($this->em);
    // проверка на ошибки валидации
        // в обязательствах
        $this->param->setRouteSearch("Обязательства");
        $this->param->setINN("hlsdhfdlshf");
            $obj->setParamSearch($this->param);
            $resultSearch =  $obj->search();
                $this->assertNotEmpty($resultSearch);
                    $this->assertEquals(1,count($resultSearch));
                         // проверяем наличие под массивов
                        // должен быть
                        $this->assertArrayHasKey("error",$resultSearch);
                            // не должно быть
                            $this->assertArrayNotHasKey("searchData",$resultSearch);
        // в кредите
        $this->param->setRouteSearch("Кредит");
        $this->param->setINN("hlsdhfdlshf");
            $obj->setParamSearch($this->param);
                $resultSearch =  $obj->search();
                    $this->assertNotEmpty($resultSearch);
                        $this->assertEquals(1,count($resultSearch));
                        // проверяем наличие под массивов
                        // должен быть
                        $this->assertArrayHasKey("error",$resultSearch);
                        // не должно быть
                        $this->assertArrayNotHasKey("searchData",$resultSearch);
        $this->clearDB();
    }
    public function test_search_DataSearch(){
        $this->loadData();
            $this->initParam();
            $obj = new searchErpnFromParam($this->em);
            // проверка на ошибки валидации
            // в обязательствах
                $this->param->setRouteSearch("Обязательства");
                    $this->param->setTypeDoc("ПНЕ");
                        $obj->setParamSearch($this->param);
                            $resultSearch =  $obj->search();
        $this->assertNotEmpty($resultSearch);
                $this->assertArrayHasKey("searchData",$resultSearch);
                    $this->assertArrayNotHasKey("error",$resultSearch);
                        $this->assertEquals(5,count($resultSearch["searchData"]));
                            $this->assertEquals(10,count($resultSearch["searchData"][0]));
        unset($resultSearch);
        // в кредите
        $this->param->setRouteSearch("Кредит");
            $this->param->setTypeDoc("ПНЕ");
                $obj->setParamSearch($this->param);
                    $resultSearch =  $obj->search();
                    $this->assertNotEmpty($resultSearch);
                $this->assertArrayHasKey("searchData",$resultSearch);
                     $this->assertArrayNotHasKey("error",$resultSearch);
                        $this->assertEquals(49,count($resultSearch["searchData"]));
                            $this->assertEquals(10,count($resultSearch["searchData"][0]));


        $this->clearDB();
    }
}
