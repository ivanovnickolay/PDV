<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.03.2019
 * Time: 15:22
 */

namespace Services\searchDataFromParam;

use App\Entity\forForm\search\docFromParam;
use App\Entity\ReestrbranchIn;
use App\Entity\ReestrbranchOut;
use App\Services\LoadReestrFromFile;
use App\Services\searchDataFromParam\searchReestrFromParam;
use App\Utilits\workToFileSystem\workWithFiles;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class searchReestrFromParamTest
 *
 *
 * Тестирование сервиса происка данных в Реестрах выданных и полученных налоговых накладных
 * таблицы базы данных  ReestrbranchOut ReestrbranchIn.
 *
 * Тестировалось
 * - получение данных из каждой таблицы отдельно
 * - генерация ошибок при ошибочных параметрах запроса из каждой таблицы отдельно
 *
 *  @see  LoadReestrFromFile Загрузка данных из файлов с использованием сервиса
 * @package Services\searchDataFromParam
 */
class searchReestrFromParamTest  extends KernelTestCase
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
     * при уничтожении объекта - очистим базу данных
     * перенесем файлы в папку для загрузки
     */
    public function __destruct()
    {
        $this->clearDB();
        try {
            $arrayFiles = workWithFiles::getArrayFilesFromDir(__DIR__ . "/Fixtures/Reestr/dirForMoveFiles");

        foreach ($arrayFiles as $file){
            workWithFiles::moveFiles(
                __DIR__."/Fixtures/Reestr/dirForMoveFiles/".basename($file),
                __DIR__."/Fixtures/Reestr/dirForLoadFiles"
            );
        }
        } catch (\Exception $e) {
}
    }

    /**
     * загрузка тестовых данных с помощи LoadReestrFromFile
     * несколько не(!) больших файлов с данными РПН (отдельно НО и отдельно НК) и загружать их при помощи LoadReestrFromFile
     * @see LoadReestrFromFile
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     */
    private function loadData(){

        $loader = new LoadReestrFromFile($this->em);
        $loader->setDirForLoadFiles(__DIR__."/Fixtures/Reestr/dirForLoadFiles");
        $loader->setDirForMoveFiles(__DIR__."/Fixtures/Reestr/dirForMoveFiles");
        $loader->setDirForMoveFilesWithError(__DIR__."/Fixtures/Reestr/dirForMoveFilesWithError");
        $loader->execute();
    }

    /**
     * удаление тестовых данных из базы данных
     */
    private function clearDB(){
        $SQLDeleteRecIn = "DELETE  FROM reestrbranch_in";

        $smtpDeleteRecIn = $this->em->getConnection()->prepare($SQLDeleteRecIn);
        $smtpDeleteRecIn->execute();

        $SQLDeleteRecOut = "DELETE  FROM reestrbranch_out";
        $smtpDeleteRecOut = $this->em->getConnection()->prepare($SQLDeleteRecOut);
        $smtpDeleteRecOut->execute();
    }

    /**
     * инициализация типовых параметров
     */
    private function initParam(){
        $this->param = new  docFromParam();
        $this->param->setMonthCreate(12);
        $this->param->setYearCreate(2016);
    }

    /**
     * проверим данные, которые загружены в базу данных путем контроля количества записей
     */
    public function test_ControlDataWithDatabase(){

        // контроль общего количества записей
           $countRecToIn=$this->em->getRepository(ReestrbranchIn::class)->count([]);
                $this->assertEquals(44,$countRecToIn);
                    $countRecToOut=$this->em->getRepository(ReestrbranchOut::class)->count([]);
                        $this->assertEquals(35,$countRecToOut);
            // контроль по условиях
                // номер филиала 611
                $countRecToIn_numBranch=$this->em->getRepository(ReestrbranchIn::class)->count(['numBranch'=>"611"]);
                    $this->assertEquals(15,$countRecToIn_numBranch);
                        $countRecToOut_numBranch=$this->em->getRepository(ReestrbranchOut::class)->count(['numBranch'=>"611"]);
                            $this->assertEquals(1,$countRecToOut_numBranch);
                // номер филиала 593
                $countRecToIn_numBranch=$this->em->getRepository(ReestrbranchIn::class)->count(['numBranch'=>"593"]);
                    $this->assertEquals(18,$countRecToIn_numBranch);
                        $countRecToOut_numBranch=$this->em->getRepository(ReestrbranchOut::class)->count(['numBranch'=>"593"]);
                            $this->assertEquals(16,$countRecToOut_numBranch);
                // номер филиала 611
                $countRecToIn_numBranch=$this->em->getRepository(ReestrbranchIn::class)->count(['numBranch'=>"588"]);
                    $countRecToOut_numBranch=$this->em->getRepository(ReestrbranchOut::class)->count(['numBranch'=>"588"]);
                        $this->assertEquals(11,$countRecToIn_numBranch);
                            $this->assertEquals(18,$countRecToOut_numBranch);

    }

    /**
     * проверка приватного метода validParamSearch - организация поиска данных в базе
     * @throws \ReflectionException
     */
    public function test_validParam(){
        $class = new \ReflectionClass(searchReestrFromParam::class);
        $method = $class->getMethod('validParamSearch');
        $method->setAccessible(true);

        $obj = new searchReestrFromParam($this->em);
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
     * для таблицы ReestrbranchIn
     * @throws \ReflectionException
     */
    public function test_getArraySearchDataReestrIn(){
        $class = new \ReflectionClass(searchReestrFromParam::class);
        $method = $class->getMethod('getArraySearchData');
        $method->setAccessible(true);
        $obj = new searchReestrFromParam($this->em);
        $this->initParam();
        $this->param->setTypeDoc("ПНЕ");
        // проверка поиска обязательств
        $this->param->setRouteSearch("Кредит");
        $obj->setParamSearch($this->param);
        $resIn = $method->invoke($obj);
        $this->assertEquals(30,count($resIn));
        $this->assertEquals(9,count($resIn[0]));
        $this->assertEquals("598",$resIn[0]["NumInvoice"]);
        $this->assertEquals(new \DateTime("2016-12-05 00:00:00.000000"),$resIn[0]["DateCreateInvoice"]);
        $this->assertEquals("ПНЕ",$resIn[0]["TypeInvoiceFull"]);
        $this->assertEquals("033614023014",$resIn[0]["InnClient"]);
        $this->assertEquals("Публічне акціонерне товариство \"по газопостачанню та газифікації \"Черкасигаз\"",$resIn[0]["NameClient"]);
        $this->assertEquals(7427.64,$resIn[0]["SumaInvoice"]);
        $this->assertEquals(6189.7,$resIn[0]["BazaInvoice"]);
        $this->assertEquals(1237.94,$resIn[0]["Pdvinvoice"]);
        //$this->assertEquals("Публічне акціонерне товариство \"по газопостачанню та газифікації \"Черкасигаз\"",$resIn[0]["NameVendor"]);
        $this->assertEquals("611",$resIn[0]["NumBranchReestr"]);
    }

    /**
     * Тестирование приватного метода getArraySearchData -  полученния данных поиска в виде массива значений
     * для таблицы ReestrbranchOut
     * @throws \ReflectionException
     */
    public function test_getArraySearchDataReestrOut(){
        $class = new \ReflectionClass(searchReestrFromParam::class);
        $method = $class->getMethod('getArraySearchData');
        $method->setAccessible(true);
        $obj = new searchReestrFromParam($this->em);
        $this->initParam();
        $this->param->setTypeDoc("ПНЕ");
        // проверка поиска обязательств
        $this->param->setRouteSearch("Обязательства");
        $obj->setParamSearch($this->param);
        $resIn = $method->invoke($obj);
        $this->assertEquals(23,count($resIn));
        $this->assertEquals(9,count($resIn[0]));
        $this->assertEquals("1//611",$resIn[0]["NumInvoice"]);
        $this->assertEquals(new \DateTime("2016-12-30 00:00:00.000000"),$resIn[0]["DateCreateInvoice"]);
        $this->assertEquals("ПНЕ",$resIn[0]["TypeInvoiceFull"]);
        $this->assertEquals("600000000000",$resIn[0]["InnClient"]);
        $this->assertEquals("ПУБЛІЧНЕ АКЦІОНЕРНЕ ТОВАРИСТВО \"УКРАЇНСЬКА ЗАЛІЗНИЦЯ\" , ПУБЛІЧНЕ АКЦІОНЕРНЕ ТОВАРИСТВО \"УКРАЇНСЬКА ЗАЛІЗНИЦЯ\" Філія \"Центр професійного розвитку персоналу\" ",$resIn[0]["NameClient"]);
        $this->assertEquals(6796.86,$resIn[0]["SumaInvoice"]);
        $this->assertEquals(5664.05,$resIn[0]["BazaInvoice"]);
        $this->assertEquals(1132.81,$resIn[0]["Pdvinvoice"]);
        //$this->assertEquals("Публічне акціонерне товариство \"по газопостачанню та газифікації \"Черкасигаз\"",$resIn[0]["NameVendor"]);
        $this->assertEquals("611",$resIn[0]["NumBranchReestr"]);
    }


    /**
     * тестирование метода search в таблице ReestrbranchOut - получение итоговых массивов по результатам валидации и запроса данных
     * тестирование ошибок валидации данных - про наличии ошибок поиск проиходит не должен
     */
    public function test_search_ErrorValidationReestrOut(){
        $obj = new searchReestrFromParam($this->em);
        $this->initParam();
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

    }


    /**
     * тестирование метода search в таблице ReestrbranchIn  - получение итоговых массивов по результатам валидации и запроса данных
     * тестирование ошибок валидации данных - про наличии ошибок поиск проиходит не должен
     *
     */
    public function test_search_ErrorValidationReestrIn(){
        $obj = new searchReestrFromParam($this->em);
        $this->initParam();
        // проверка на ошибки валидации
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
     * Тест получения информации по коду ИПН в периоде
     */

    public function test_searchDataToReestrOut_INN(){
        $this->initParam();
        $this->param->setINN("352449120316"); ///002110021058
        $this->param->setRouteSearch("Обязательства");
        $obj=new searchReestrFromParam($this->em);
        $obj->setParamSearch($this->param);
        $resultSearch =  $obj->search();
        $this->assertNotEmpty($resultSearch);
        $this->assertArrayHasKey("searchData",$resultSearch);
        $this->assertArrayNotHasKey("error",$resultSearch);
        $this->assertEquals(2,count($resultSearch["searchData"]));
        $this->assertEquals(9,count($resultSearch["searchData"][0]));

        unset($resultSearch);

        $this->initParam();
        $this->param->setINN("002110021058");
        $obj=new searchReestrFromParam($this->em);
        $this->param->setRouteSearch("Обязательства");
        $obj->setParamSearch($this->param);
        $resultSearch =  $obj->search();
        $this->assertNotEmpty($resultSearch);
        $this->assertArrayHasKey("searchData",$resultSearch);
        $this->assertArrayNotHasKey("error",$resultSearch);
        $this->assertEquals(9,count($resultSearch["searchData"]));
    }

    /**
     * тестирование посика документов по ИНН и типу документа
     */

    public function test_test_searchDataToReestrOut_INN_typeDoc(){
        $this->initParam();
        $this->param->setINN("400000000000");
        $this->param->setTypeDoc("ПНЕ");
        $this->param->setRouteSearch("Обязательства");
        $obj=new searchReestrFromParam($this->em);
        $obj->setParamSearch($this->param);
        $resultSearch =  $obj->search();
        $this->assertNotEmpty($resultSearch);
        $this->assertArrayHasKey("searchData",$resultSearch);
        $this->assertArrayNotHasKey("error",$resultSearch);
        $this->assertEquals(1,count($resultSearch["searchData"]));

        unset($resultSearch,$obj);

        $this->initParam();
        $this->param->setINN("400000000000");
        $this->param->setRouteSearch("Обязательства");
        $this->param->setTypeDoc("РКЕ");
        $obj=new searchReestrFromParam($this->em);
        $obj->setParamSearch($this->param);
        $resultSearch =  $obj->search();
        $this->assertNotEmpty($resultSearch);
        $this->assertArrayHasKey("searchData",$resultSearch);
        $this->assertArrayNotHasKey("error",$resultSearch);
        $this->assertEquals(2,count($resultSearch["searchData"]));

    }
    /**
     *
     */
    public function test_searchDataToReestrOut_dateCreate(){
        $this->initParam();
        $this->param->setDateCreateDoc(new \DateTime("13.12.2016"));
        $this->param->setTypeDoc("ПНЕ");
        $this->param->setRouteSearch("Обязательства");
        $obj=new searchReestrFromParam($this->em);
        $obj->setParamSearch($this->param);
        $resultSearch =  $obj->search();
        $this->assertNotEmpty($resultSearch);
        $this->assertArrayHasKey("searchData",$resultSearch);
        $this->assertArrayNotHasKey("error",$resultSearch);
        $this->assertEquals(4,count($resultSearch["searchData"]));

        unset($resultSearch,$obj);

        $this->initParam();
        $this->param->setDateCreateDoc(new \DateTime("31.12.2016"));
        $this->param->setTypeDoc("РКЕ");
        $this->param->setRouteSearch("Обязательства");
        $obj=new searchReestrFromParam($this->em);
        $obj->setParamSearch($this->param);
        $resultSearch =  $obj->search();
        $this->assertNotEmpty($resultSearch);
        $this->assertArrayHasKey("searchData",$resultSearch);
        $this->assertArrayNotHasKey("error",$resultSearch);
        $this->assertEquals(12,count($resultSearch["searchData"]));



    }
}