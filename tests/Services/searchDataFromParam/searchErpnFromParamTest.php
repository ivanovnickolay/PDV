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
use App\Services\searchDataFromParam\searchErpnFromParam;
use Doctrine\DBAL\Driver\Connection;
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
        // загрузка тестовых данных
        $this->loadData();
    }

    /**
     * при уничтожении объекта - ощистим базу данных
     */
    public function __destruct()
    {
        $this->clearDB();
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
        $this->assertEquals("ИНН hlsdhfdlshf должен содержать только цифры ",$res1["INN"]);

        $this->param->setDateCreateDoc(new \DateTime("2018-01-01"));
        $res2 = $method->invoke($obj);
        $this->assertEquals(2,count($res2));
        $this->assertEquals("Период поиска документа и дата создания документа должны совпадать !",$res2["dateCreateDoc"]);
    }


    /**
     * Тестирование приватного метода getArraySearchData -  полученния данных поиска в виде массива значений
     * @throws \ReflectionException
     */
    public function test_getArraySearchData(){

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

    }

    /**
     * тестирование метода search - получение итоговых массивов по результатам валидации и запроса данных
     * тестирование ошибок валидации данных - про наличии ошибок поиск проиходит не должен
     */
    public function test_search_ErrorValidation(){

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

    }

    /**
     * тестирование получения данных при произвольных запросах из таблицы ErpnOut
     * получить все документы из базы
     */
    public function test_search_DataSearchToErpnOut_AllDoc(){

        $this->initParam();
        $obj = new searchErpnFromParam($this->em);
        // проверка на ошибки валидации
        // в обязательствах
        // Получим все ПНЕ из базы (плохой запрос)
        $this->param->setRouteSearch("Обязательства");
            $this->param->setTypeDoc("ПНЕ");
            $obj->setParamSearch($this->param);
                $resultSearch =  $obj->search();
                    $this->assertNotEmpty($resultSearch);
                        $this->assertArrayHasKey("searchData",$resultSearch);
                        $this->assertArrayNotHasKey("error",$resultSearch);
                    $this->assertEquals(5,count($resultSearch["searchData"]));
                $this->assertEquals(10,count($resultSearch["searchData"][0]));

           }
    /**
     * тестирование получения данных при произвольных запросах из таблицы ErpnOut
     * Получим все ПНЕ из базы с ИПН 203387405140
     */
    public function test_search_DataSearchToErpnOut_INN(){

        $this->initParam();
        $obj = new searchErpnFromParam($this->em);
        // проверка на ошибки валидации
        // в обязательствах
       // Получим все ПНЕ из базы с ИПН 203387405140
        $this->param->setRouteSearch("Обязательства");
        $this->param->setINN("203387405140");
        $this->param->getTypeDoc("ПНЕ");
        $obj->setParamSearch($this->param);
        $resultSearch =  $obj->search();
        $this->assertNotEmpty($resultSearch);
        $this->assertArrayHasKey("searchData",$resultSearch);
        $this->assertArrayNotHasKey("error",$resultSearch);
        $this->assertEquals(2,count($resultSearch["searchData"]));
        $this->assertEquals(10,count($resultSearch["searchData"][0]));
    }

    /**
     * тестирование получения данных при произвольных запросах из таблицы ErpnOut
     * Получим все РКЕ из базы датой создания 31.03.2017
     */
    public function test_search_DataSearchToErpnOut_DateCreate_RKE()
    {
        $this->initParam();
        $obj = new searchErpnFromParam($this->em);
        // Получим все РКЕ из базы датой создания 31.03.2017
        $this->param->setRouteSearch("Обязательства");
        $this->param->setDateCreateDoc(new \DateTime("31.03.2017"));
                $this->param->setTypeDoc("РКЕ");
        $obj->setParamSearch($this->param);
        $resultSearch =  $obj->search();
        $this->assertNotEmpty($resultSearch);
        $this->assertArrayHasKey("searchData",$resultSearch);
        $this->assertArrayNotHasKey("error",$resultSearch);
        $this->assertEquals(7,count($resultSearch["searchData"]));


    }

    /**
     * тестирование получения данных при произвольных запросах из таблицы ErpnOut
     * Получим все ПНЕ из базы с номером документа 1//571
     */
    public function test_search_DataSearchToErpnOut_numDoc()
    {
        $this->initParam();
        $obj = new searchErpnFromParam($this->em);
        $this->param->setRouteSearch("Обязательства");
        $this->param->setNumDoc("1//571");
        $this->param->setTypeDoc("ПНЕ");
        $obj->setParamSearch($this->param);
        $resultSearch =  $obj->search();
        $this->assertNotEmpty($resultSearch);
        $this->assertArrayHasKey("searchData",$resultSearch);
        $this->assertArrayNotHasKey("error",$resultSearch);
        $this->assertEquals(1,count($resultSearch["searchData"]));
        unset($resultSearch);

    }

    /**
     * тестирование получения данных при произвольных запросах в таблице ErpnIn
     * Получить все документы из базы
     */
    public function test_search_DataSearchToErpnIn_AllDoc(){

        $this->initParam();
        $obj = new searchErpnFromParam($this->em);
               $this->param->setRouteSearch("Кредит");
                    $this->param->setTypeDoc("ПНЕ");
                        $obj->setParamSearch($this->param);
                            $resultSearch =  $obj->search();
                                $this->assertNotEmpty($resultSearch);
                                    $this->assertArrayHasKey("searchData",$resultSearch);
                                        $this->assertArrayNotHasKey("error",$resultSearch);
                                $this->assertEquals(49,count($resultSearch["searchData"]));
                        $this->assertEquals(10,count($resultSearch["searchData"][0]));
    }

    /**
     * тестирование получения данных при произвольных запросах в таблице ErpnIn
     * Получить все документы из базы по номеру документа
     */
    public function test_search_DataSearchToErpnIn_numDoc(){

        $this->initParam();
        $obj = new searchErpnFromParam($this->em);
        $this->param->setRouteSearch("Кредит");
        $this->param->setNumDoc("1");
        $this->param->setTypeDoc("ПНЕ");
        $obj->setParamSearch($this->param);
        $resultSearch =  $obj->search();
        $this->assertNotEmpty($resultSearch);
        $this->assertArrayHasKey("searchData",$resultSearch);
        $this->assertArrayNotHasKey("error",$resultSearch);
        $this->assertEquals(2,count($resultSearch["searchData"]));
        $this->assertEquals(10,count($resultSearch["searchData"][0]));
        unset($resultSearch,$this->param);
            $this->initParam();
            $obj = new searchErpnFromParam($this->em);
            $this->param->setRouteSearch("Кредит");
            $this->param->setNumDoc("1");
            $this->param->setTypeDoc("РКЕ");
            $obj->setParamSearch($this->param);
            $resultSearch =  $obj->search();
            $this->assertNotEmpty($resultSearch);
            $this->assertArrayHasKey("searchData",$resultSearch);
            $this->assertArrayNotHasKey("error",$resultSearch);
            $this->assertEquals(2,count($resultSearch["searchData"]));
            $this->assertEquals(10,count($resultSearch["searchData"][0]));
    }

    /**
     * тестирование получения данных при произвольных запросах из таблицы ErpnOut
     * Получим все ПНЕ из базы с ИПН 331188920305
     */
    public function test_search_DataSearchToErpnIn_INN(){

        $this->initParam();
        $obj = new searchErpnFromParam($this->em);
        // проверка на ошибки валидации
        // в обязательствах
        // Получим все ПНЕ из базы с ИПН 203387405140
        $this->param->setRouteSearch("Кредит");
        $this->param->setINN("331188920305");
        $this->param->setTypeDoc("ПНЕ");
        $obj->setParamSearch($this->param);
        $resultSearch =  $obj->search();
        $this->assertNotEmpty($resultSearch);
        $this->assertArrayHasKey("searchData",$resultSearch);
        $this->assertArrayNotHasKey("error",$resultSearch);
        $this->assertEquals(2,count($resultSearch["searchData"]));
        $this->assertEquals(10,count($resultSearch["searchData"][0]));
    }

    /**
     * тестирование получения данных при произвольных запросах из таблицы ErpnOut
     * Получим все ПНЕ из базы с датой создания 31.03.2017
     */
    public function test_search_DataSearchToErpnIn_DateCreate(){

        $this->initParam();
        $obj = new searchErpnFromParam($this->em);
        // проверка на ошибки валидации
        // в обязательствах
       $this->param->setRouteSearch("Кредит");
        $this->param->setDateCreateDoc(new \DateTime("31.03.2017"));
        $this->param->setTypeDoc("ПНЕ");
        $obj->setParamSearch($this->param);
        $resultSearch =  $obj->search();
        $this->assertNotEmpty($resultSearch);
        $this->assertArrayHasKey("searchData",$resultSearch);
        $this->assertArrayNotHasKey("error",$resultSearch);
        $this->assertEquals(12,count($resultSearch["searchData"]));
        $this->assertEquals(10,count($resultSearch["searchData"][0]));
    }

}
