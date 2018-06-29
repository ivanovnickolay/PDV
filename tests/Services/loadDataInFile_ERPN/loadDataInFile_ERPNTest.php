<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 29.06.2018
 * Time: 14:58
 */

namespace App\Services;


use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\{
    Bundle\FrameworkBundle\Test\KernelTestCase
};

/**
 * Class loadDataInFile_ERPNTest тестирование класса для организации загрузки данных из файлов в таблицы базы данных
 *  - ЕРПН Выданные
 *  - ЕРПН Полученные
 *
 * Одновременно тестируются репозитории сущностей
 * -    erpn_in
 * -    erpn_out
 *
 * @package Services
 */
class loadDataInFile_ERPNTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @throws \Exception
     */
    public function setUp(){
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
     * Тестирование на бросание исключения при отсутствии папок для работы класса
     * @throws errorLoadDataException
     */
    public function test__constructError(){
        $this->expectException(errorLoadDataException::class);
        $obj = new loadDataInFile_ERPN($this->em, "", "");
        $this->assertInstanceOf(loadDataInFile_ERPN::class,$obj);
    }

    /**
     * Тест на чтение файла с данными для ЕРПН Полученные
     * @throws \Doctrine\DBAL\DBALException
     * @throws errorLoadDataException
     */
    public function testLoad_ERPN_In(){
        $obj = new loadDataInFile_ERPN($this->em,
            __DIR__."\\In\\", __DIR__."\\Out\\");
            $obj->load_ERPN_In("template_CSV_In(03_2017do_zagruzka).csv");
                $this->validERPN_In();
    }

    /**
     * Тест на чтение файла с данными для ЕРПН Выданные
     * @throws \Doctrine\DBAL\DBALException
     * @throws errorLoadDataException
     */
    public function testLoad_ERPN_Out(){
        $obj = new loadDataInFile_ERPN($this->em,
            __DIR__."\\In\\", __DIR__."\\Out\\");
        $obj->load_ERPN_Out("template_CSV_Out(03_2017do_zagruzka).csv");
        $this->validERPN_Out();

    }

    /**
     * валидация данных загруженных в ЕРПН Выданные
     * @throws \Doctrine\DBAL\DBALException
     */
    private function validERPN_In(){
        $sqlCountRec = "SELECT count(id),sum(pdvinvoice) FROM erpn_in";
        $smtp = $this->em->getConnection()->prepare($sqlCountRec);
        $smtp->execute();
        $arrayResult = $smtp->fetchAll();
        $this->assertEquals(60, $arrayResult[0]['count(id)']);
        $this->assertEquals(505334.66, $arrayResult[0]['sum(pdvinvoice)']);

        $SQLDeleteRec = "DELETE  FROM erpn_in";
        $smtpDeleteRec = $this->em->getConnection()->prepare($SQLDeleteRec);
        $smtpDeleteRec->execute();
    }
    /**
     * валидация данных загруженных в ЕРПН Полученные
     * @throws \Doctrine\DBAL\DBALException
     */
    private function validERPN_Out(){
        $sqlCountRec = "SELECT count(id),sum(pdvinvoice) FROM erpn_out";
        $smtp = $this->em->getConnection()->prepare($sqlCountRec);
        $smtp->execute();
        $arrayResult = $smtp->fetchAll();
        $this->assertEquals(12, $arrayResult[0]['count(id)']);
        $this->assertEquals(13122.92, $arrayResult[0]['sum(pdvinvoice)']);

        $SQLDeleteRec = "DELETE  FROM erpn_out";
        $smtpDeleteRec = $this->em->getConnection()->prepare($SQLDeleteRec);
        $smtpDeleteRec->execute();

    }
}