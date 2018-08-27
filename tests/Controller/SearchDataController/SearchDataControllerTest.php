<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 19.07.2018
 * Time: 00:47
 */

use App\Controller\SearchDataController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchDataControllerTest extends WebTestCase
{
    /**
     * загрузка тестовых данных
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \Doctrine\DBAL\DBALException
     */
    private function loadData(\Doctrine\ORM\EntityManagerInterface $entityManager){
        $fileNameIn  = __DIR__."//Fixtures//template_CSV_In(03_2017do_zagruzka).csv";
        $entityManager->getRepository(\App\Entity\ErpnIn::class)->loadDataInFile($fileNameIn);

        $fileNameOut  = __DIR__."//Fixtures//template_CSV_Out(03_2017do_zagruzka).csv";
        $entityManager->getRepository(\App\Entity\ErpnOut::class)->loadDataInFile($fileNameOut);
    }

    /**
     * удаление тестовых данных из базы данных
     */
    private function clearDB(\Doctrine\ORM\EntityManagerInterface $entityManage){
        $SQLDeleteRecIn = "DELETE  FROM erpn_in";
        $smtpDeleteRecIn = $entityManage->getConnection()->prepare($SQLDeleteRecIn);
        $smtpDeleteRecIn->execute();

        $SQLDeleteRecOut = "DELETE  FROM erpn_out";
        $smtpDeleteRecOut = $entityManage->getConnection()->prepare($SQLDeleteRecOut);
        $smtpDeleteRecOut->execute();
    }

    /**
     * получение EntityManager из контейнера
     * @link https://symfony.com/blog/new-in-symfony-4-1-simpler-service-testing
     * @return \Doctrine\ORM\EntityManager|object
     * @throws Exception
     */
    private function getEm(){
        $container = self::$container;
        $em = $container->get('Doctrine\ORM\EntityManagerInterface');
        if ($em->getConnection()->getDatabase()!="AnalizPDV_test"){
            throw new \Exception();
        }
        return $em;
    }

    /**
     * Тестирование отображение страницы с формой
     */
    public function testSearchERPN_BlankForm(){
        $client = static::createClient();
            $client->request('GET','/search');
                $this->assertContains("<title>Анализ ПДВ </title>",$client->getResponse()->getContent());
                    $this->assertContains("<form name=\"search_erpn\" method=\"post\" novalidate=\"novalidate\">",$client->getResponse()->getContent());
        $this->assertContains(" <h3>Поиск документов в ЕРПН за период </h3>",$client->getResponse()->getContent());
    }

    /**
     * Тестирование отправки Ajax запроса по обязательствам и получения ответа с содержанием ошибки
     * тестирование с пробным набором данных
     * @throws Exception
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function testSearchERPN_Out_SendAjax_ResponseError(){

        $client = static::createClient();

        $em=$this->getEm();
            $this->loadData($em);

        $client->xmlHttpRequest('POST','/search',array(
            "search_erpn"=>array(
                "monthCreate"=>"3",
                "yearCreate"=>"2017",
                "numDoc"=>null,
                "dateCreateDoc"=>null,
                "typeDoc"=>"ПНЕ",
                "iNN"=>"hlsdhfdlshf",
                "routeSearch"=>"Обязательства"
            )
        ));
        $dataReturn = json_decode($client->getResponse()->getContent());
        // проверим количество ошибок
            $this->assertEquals(1,count($dataReturn->error));
                // проверим содержание ошибки
                $this->assertEquals("ИНН \"hlsdhfdlshf\" должен содержать только цифры .",$dataReturn->error->INN);
                    // проверим код возврата
                    $this->assertEquals(
                200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode());

        $this->clearDB($em);
    }

    /**
     * Тестирование отправки Ajax запроса по кредиту и получения ответа с содержанием ошибки
     * тестирование с пробным набором данных
     * @throws Exception
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function testSearchERPN_In_SendAjax_ResponseError(){

        $client = static::createClient();

        $em=$this->getEm();
            $this->loadData($em);

        $client->xmlHttpRequest('POST','/search',array(
            "search_erpn"=>array(
                "monthCreate"=>"3",
                "yearCreate"=>"2017",
                "numDoc"=>null,
                "dateCreateDoc"=>null,
                "typeDoc"=>"РКЕ",
                "iNN"=>"hlsdhfdlshf",
                "routeSearch"=>"Кредит"
            )
        ));

        $dataReturn = json_decode($client->getResponse()->getContent());
        // проверим количество ошибок
        $this->assertEquals(1,count($dataReturn->error));
        // проверим содержание ошибки
        $this->assertEquals("ИНН \"hlsdhfdlshf\" должен содержать только цифры .",$dataReturn->error->INN);
        // проверим код возврата
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode());

        $this->clearDB($em);
    }

    /**
     * Тестирование отправки Ajax запроса по обязательствам и получения ответа с данными
     * тестирование с пробным набором данных
     * @throws Exception
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function testSearchERPN_Out_SendAjax_ResponseSuccess(){

        $client = static::createClient();

        $em=$this->getEm();
        $this->loadData($em);

        $client->xmlHttpRequest('POST','/search',array(
            "search_erpn"=>array(
                "monthCreate"=>"3",
                "yearCreate"=>"2017",
                "numDoc"=>null,
                "dateCreateDoc"=>null,
                "typeDoc"=>"ПНЕ",
                "iNN"=>null,
                "routeSearch"=>"Обязательства"
            )
        ));
        $dataReturn = json_decode($client->getResponse()->getContent());
        // проверим количество записей с данными
        $this->assertEquals(5,count($dataReturn->searchData));
            // проверим содержимое возврата по первой строке с данными
            $this->assertEquals("1//571",$dataReturn->searchData[0]->NumInvoice);
                $this->assertEquals(new \DateTime('2017-03-01'),new \DateTime($dataReturn->searchData[0]->DateCreateInvoice->date));
                    $this->assertEquals("ПНЕ",$dataReturn->searchData[0]->TypeInvoiceFull);
                        $this->assertEquals("009558518131",$dataReturn->searchData[0]->InnClient);
                     $this->assertEquals("ПРИВАТНЕ АКЦІОНЕРНЕ ТОВАРИСТВО \"БІЛОВОДСЬКИЙ КОМБІНАТ ХЛІБОПРОДУКТІВ\"",$dataReturn->searchData[0]->NameClient);
                 $this->assertEquals("2083.96",$dataReturn->searchData[0]->SumaInvoice);
            $this->assertEquals("1736.63",$dataReturn->searchData[0]->BazaInvoice);
        $this->assertEquals("347.33",$dataReturn->searchData[0]->Pdvinvoice);
             $this->assertEquals("/філія \"Південна залізниця\" ПАТ \"Укрзалізниця\"/ СП \"Харківський центр професійної освіти\" філії \"Південна залізниця\" ПАТ \"Укрзалізниця\"",$dataReturn->searchData[0]->NameVendor);
                    $this->assertEquals("571",$dataReturn->searchData[0]->NumBranchVendor);
        // проверим код возврата
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode());

        $this->clearDB($em);
    }

    /**
     * Тестирование отправки Ajax запроса по кредиту и получения ответа с данными
     * тестирование с пробным набором данных
     * @throws Exception
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function testSearchERPN_In_SendAjax_ResponseSuccess(){

        $client = static::createClient();

        $em=$this->getEm();
        $this->loadData($em);

        $client->xmlHttpRequest('POST','/search',array(
            "search_erpn"=>array(
                "monthCreate"=>"3",
                "yearCreate"=>"2017",
                "numDoc"=>null,
                "dateCreateDoc"=>null,
                "typeDoc"=>"ПНЕ",
                "iNN"=>null,
                "routeSearch"=>"Кредит"
            )
        ));
        /**
         * $client->getResponse()->getContent()
         */
        $dataReturn = json_decode($client->getResponse()->getContent());
        // проверим количество записей с данными
        $this->assertEquals(49,count($dataReturn->searchData));
        // проверим содержимое возврата по первой строке с данными
        $this->assertEquals("1",$dataReturn->searchData[0]->NumInvoice);
        $this->assertEquals(new \DateTime('2017-03-23'),new \DateTime   ($dataReturn->searchData[0]->DateCreateInvoice->date));
        $this->assertEquals("ПНЕ",$dataReturn->searchData[0]->TypeInvoiceFull);
        $this->assertEquals("345012604630",$dataReturn->searchData[0]->InnClient);
        $this->assertEquals("ТОВАРИСТВО З ОБМЕЖЕНОЮ ВІДПОВІДАЛЬНІСТЮ \"КОМПАНІЯ ПРОМІНСТРУМЕНТ\"",$dataReturn->searchData[0]->NameClient);
        $this->assertEquals("140272.74",$dataReturn->searchData[0]->SumaInvoice);
        $this->assertEquals("116893.95",$dataReturn->searchData[0]->BazaInvoice);
        $this->assertEquals("23378.79",$dataReturn->searchData[0]->Pdvinvoice);
        $this->assertEquals("ПУБЛІЧНЕ АКЦІОНЕРНЕ ТОВАРИСТВО \"УКРАЇНСЬКА ЗАЛІЗНИЦЯ\" РЕГІОНАЛЬНА ФІЛІЯ \"ДОНЕЦЬКА ЗАЛІЗНИЦЯ\" СТРУКТУРНИЙ ПІДРОЗДІЛ \"ДОНЕЦЬКИЙ ГОЛОВНИЙ МАТЕРІАЛЬНО-ТЕХНІЧНИЙ СКЛАД\"",$dataReturn->searchData[0]->NameVendor);
        $this->assertEquals("779",$dataReturn->searchData[0]->NumBranchVendor);
        // проверим код возврата
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode());

        $this->clearDB($em);
    }

}
