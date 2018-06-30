<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.06.2018
 * Time: 21:49
 */

namespace App\Command;


use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class LoadDataToErpnOutCommandTest тестирование комманды LoadDataToErpnOutCommand
 * на контрольное чтение файла с данными
 * @package App\Command
 */
class LoadDataToErpnOutCommandTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    private $dirForFilesERPN_Out;
    private $dirForFilesERPN_In;

    public function setUp()
    {
        // получаем Entity Manager
        $kernel = self::bootKernel();
        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $rr = $this->em->getConnection()->getDatabase();
        if ("AnalizPDV_test" != $rr) {
            throw new \Exception();
        }
        $this->dirForFilesERPN_Out = $kernel->getContainer()->getParameter('dirForFilesERPN_Out');
        $this->dirForFilesERPN_In = $kernel->getContainer()->getParameter("dirForFilesERPN_In");

    }
    public function test__construct()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $command = $application->find('loadDataToErpnOut');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'fileName'=>'template_CSV_Out(03_2017do_zagruzka).csv'
        ));
        $this->validExecute();

    }

    private function validExecute(){
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
