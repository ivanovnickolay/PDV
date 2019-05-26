<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28.06.2018
 * Time: 14:33
 */

namespace Command;

use App\Command\LoadDataFromFilesCommand;
use App\Services\LoadReestrFromFile;
use App\Utilits\workToFileSystem\workWithFiles;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class LoadDataFromFilesCommandTest тестирование работы команды loadDataFromFiles
 * в процессе тестирование проведем пробное чтение одного файла
 * @package Command
 */
class LoadDataFromFilesCommandTest extends KernelTestCase
{


    private $em;
    private $dirForMoveFilesWithError;
    private $dirForMoveFiles;
    private $dirFixturesFiles;

    public function setUp():void {
        ini_set( 'display_errors', '1' );
        // получаем Entity Manager
        $kernel = self::bootKernel();
        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $rr=$this->em->getConnection()->getDatabase();
        if ("AnalizPDV_test"!=$rr){
            throw new \Exception();
        }
        $this->dirFixturesFiles = $kernel->getContainer()->getParameter('dirFixturesFiles');
        $toFiles = $kernel->getContainer()->getParameter('dirForLoadFiles');
        $this->dirForMoveFilesWithError = $kernel->getContainer()->getParameter("dirForMoveFilesWithError");
        $this->dirForMoveFiles = $kernel->getContainer()->getParameter("dirForMoveFiles");
        workWithFiles::moveFiles(
           $this->dirFixturesFiles."//testDataСorrectReestrIn_TAB1.xls" ,
           $toFiles
        );

    }

    public function test__construct()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $command = $application->find('loadDataFromFiles');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array());
        $this->validExecute();

        // вернем файлы обратно
        workWithFiles::moveFiles(
            $this->dirForMoveFiles."\\testDataСorrectReestrIn_TAB1.xls",
            $this->dirFixturesFiles);


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
            // контроль общей загруженной суммы всех документов
            $SQLSumZagSumm = "SELECT sum(zag_summ) FROM reestrbranch_in";
            $smtpSumZagSumm = $this->em->getConnection()->prepare($SQLSumZagSumm);
            $smtpSumZagSumm->execute();
            $arrayResult = $smtpSumZagSumm->fetchAll();
            $this->assertEquals("122519.56", $arrayResult[0]['sum(zag_summ)']);
            $this->deleteAllFromReestrIn();
        }
        /**
         * Принудительное удаление всех даннных их таблицы reestrbranch_in
         * @throws \Doctrine\DBAL\DBALException
         */
        private function deleteAllFromReestrIn(): void
        {
            // очистим таблицу с данными
            $SQLDeleteRec = "DELETE  FROM reestrbranch_in";
            $smtpDeleteRec = $this->em->getConnection()->prepare($SQLDeleteRec);
            $smtpDeleteRec->execute();
        }

}
