<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14.07.2018
 * Time: 21:52
 */

namespace App\Entity\Repository;

use App\Entity\ErpnIn;
use App\Entity\ErpnOut;
use App\Entity\forForm\search\docFromParam;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * тестирование метода searchDataFromParam репозитория ErpnInRepository
 * Class searchErpnTest
 * @package App\Entity\Repository
 */
class searchErpnOutTest extends KernelTestCase
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager|object
     */
    private $em;
    /**
     * @var ErpnInRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;
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
        $this->repository =  $this->em->getRepository(ErpnOut::class);


    }

    /**
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function setUp():void{
        $this->loadData();
    }
    /**
     * загрузка тестовых данных для поиска
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \Doctrine\DBAL\DBALException
     */
    private function loadData(){
        $fileName  = __DIR__."//Fixtures//template_CSV_Out(03_2017do_zagruzka).csv";
        $this->repository->loadDataInFile($fileName);
    }

    /**
     * Очистка базы
     */
    private function clearDB(){
        $SQLDeleteRec = "DELETE  FROM erpn_out";
        $smtpDeleteRec = $this->em->getConnection()->prepare($SQLDeleteRec);
        $smtpDeleteRec->execute();
    }

    /**
     * инициализация типовых параметров
     */
    public function initParam(){
        $this->param = new  docFromParam();
        $this->param->setMonthCreate(3);
        $this->param->setYearCreate(2017);
    }

    public function test_searchTypeDoc(){
        $this->initParam();

            $this->param->setTypeDoc("ПНЕ");
            $res1 = $this->repository->searchDataFromParam($this->param);
            $this->assertEquals(5,count($res1));

                $this->param->setTypeDoc("РКЕ");
                $res2 = $this->repository->searchDataFromParam($this->param);
                $this->assertEquals(7,count($res2));

        unset($this->param);
    }

    public function test_searchTypeDoc_INN(){
        $this->initParam();

            $this->param->setTypeDoc("ПНЕ");
            $this->param->setINN("203387405140");
            $res1 = $this->repository->searchDataFromParam($this->param);
            $this->assertEquals(2,count($res1));

                $this->param->setTypeDoc("РКЕ");
                $this->param->setINN("319411720303");
                $res2 = $this->repository->searchDataFromParam($this->param);
                $this->assertEquals(4,count($res2));

        unset($this->param);
    }
    public function test_searchTypeDoc_INN_NumDoc(){
        $this->initParam();

            $this->param->setTypeDoc("ПНЕ");
            $this->param->setINN("203387405140");
            $this->param->setNumDoc("2//638");
            $res1 = $this->repository->searchDataFromParam($this->param);
            $this->assertEquals(1,count($res1));

                $this->param->setTypeDoc("РКЕ");
                $this->param->setINN("319411720303");
                $this->param->setNumDoc("2309//686");
                $res2 = $this->repository->searchDataFromParam($this->param);
                $this->assertEquals(1,count($res2));
        unset($this->param);
    }

    public function test_searchTypeDoc_DateCreate(){
        $this->initParam();

            $this->param->setTypeDoc("ПНЕ");
            $this->param->setDateCreateDoc(new \DateTime('2017-03-22'));
            $res1 = $this->repository->searchDataFromParam($this->param);
            $this->assertEquals(1,count($res1));

                $this->param->setTypeDoc("РКЕ");
                $this->param->setDateCreateDoc(new \DateTime('2017-03-31'));
                $res2 = $this->repository->searchDataFromParam($this->param);
                $this->assertEquals(7,count($res2));

        unset($this->param);
    }

    public function tearDown():void {
        $this->clearDB();
    }
}
