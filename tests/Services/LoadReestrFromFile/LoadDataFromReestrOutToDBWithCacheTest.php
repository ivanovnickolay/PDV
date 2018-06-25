<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31.05.2018
 * Time: 21:39
 */

namespace App\Services;

use App\Entity\Repository\SprBranchRepository;
use App\Entity\SprBranch;
use App\Utilits\loadDataExcel\cacheDataRow\cacheDataRow;
use App\Utilits\workToFileSystem\workWithFiles;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Тестирование загрузки данных из ReestrOut
 * Class LoadDataFromReestrOutToDBTest
 * @package App\Services
 */
class LoadDataFromReestrOutToDBWithCacheTest extends KernelTestCase
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @throws \Exception если база не тестовая AnalizPDV_test
     */
    public function setUp(){

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

    public function test_EntityManager(){
        $this->assertInstanceOf(EntityManager::class,$this->em);
    }

    /**
     * контроль чтения данных из файла testDataСorrectReestrOut_TAB2.xls
     *  - переносим файл в папку для чтения
     *  - загружаем в БД данные из файла
     *  - контролируем количество загруженных записей и обшую сумму
     *  - очищаем БД
     *  - переносим файл в папку дял хранения
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function test_loadCorrectReestrOut(){
        $fileName = "testDataСorrectReestrOut_TAB2.xls";
            $logName = "testDataСorrectReestrOut_TAB2.log";
                $this->prepareFileForLoad($fileName,$logName);
                    $this->loadReestrFromFile();
                        $this->validCorrectLoadReestrOut();
                    $this->moveFileToFixtures($fileName,$logName);

    }

    /**
     * Проверка на загрузку данных которые уже есть в базе
     *  -   загружаем в чистую таблицу данные из файла
     *  -   повторно загружаем данные их этого файла
     *  -   проверяем
     *      - файл с данными должен появится в dirForMoveFilesWithError
     *      - в dirForMoveFilesWithError должен появится лог с ошибкой "Филиал уже подавал РПН за этот период ранее"
     *  -  удаляпем данные из таблицы
     *  -  переносим файл с данными в   fixturesFiles
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function test_doubleLoadCorrectReestrOut(){
        $fileName = "testDataСorrectReestrOut_TAB2.xls";
            $logName = "testDataСorrectReestrOut_TAB2.log";
                $this->prepareFileForLoad($fileName,$logName);
                    $this->loadReestrFromFile();
                $this->moveFileToFixtures($fileName,$logName);
                    $this->prepareFileForLoad($fileName,$logName);
                        $this->loadReestrFromFile();

                        $this->validDoubleLoadCorrectReestrOut($logName);
                    $this->deleteAllFromReestrOut();
                workWithFiles::moveFiles(
                    __DIR__ . '\\dirForMoveFilesWithError\\'.$fileName,
                    __DIR__ . '\\fixturesFiles');
                if (file_exists(__DIR__ . '\\dirForMoveFilesWithError\\'.$logName)) {
                    unlink(__DIR__ . '\\dirForMoveFilesWithError\\'.$logName);
                }
    }

    /**
     * Тест доступность репозитория
     */
    public function test_getRepository(){
        $repo = $this->em->getRepository(SprBranch::class);
        $this->assertInstanceOf(SprBranchRepository::class,$repo);
    }

    /**
     * подготовка файлов с даннми для загрузки
     * @param string $fileName
     * @param string $logName
     */
    private function prepareFileForLoad(string $fileName,string $logName): void
    {
// подготовим файлы для загрузки
        workWithFiles::moveFiles(
            __DIR__ . '\\fixturesFiles\\'.$fileName,
            __DIR__ . '\\dirForLoadFiles');
        if (file_exists(__DIR__ . '\\dirForMoveFilesWithError\\'.$logName)) {
            unlink(__DIR__ . '\\dirForMoveFilesWithError\\'.$logName);
        }
    }

    /**
     * загрузка файлов
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     */
    private function loadReestrFromFile(): void
    {
        $obj = new LoadReestrFromFile($this->em);
        $obj->setDirForLoadFiles(__DIR__ . '\\dirForLoadFiles');
        $obj->setDirForMoveFiles(__DIR__ . '\\dirForMoveFiles');
        $obj->setDirForMoveFilesWithError(__DIR__ . '\\dirForMoveFilesWithError');
        $obj->setCache(new cacheDataRow());
        $obj->execute();
    }

    /**
     * Проверка результатов корректной загрузки данных из файла testDataСorrectReestrOut_TAB2
     * @throws \Doctrine\DBAL\DBALException
     */
    private function validCorrectLoadReestrOut(): void
    {
        $this->assertFileNotExists(__DIR__ . '\\dirForMoveFilesWithError\testDataСorrectReestrOut_TAB2.xls');
        $this->assertFileExists(__DIR__ . '\\dirForMoveFiles\testDataСorrectReestrOut_TAB2.xls');
        // Проведем проверку что загрузилось
        // контроль количества записей
        $SQLCountRec = "SELECT COUNT(id) FROM reestrbranch_out";
        $smtpCountRec = $this->em->getConnection()->prepare($SQLCountRec);
        $smtpCountRec->execute();
        $arrayResult = $smtpCountRec->fetchAll();
        $this->assertEquals(7, $arrayResult[0]['COUNT(id)']);
        // контроль общей загруженной суммы всех документов
        $SQLSumZagSumm = "SELECT sum(zag_summ) FROM reestrbranch_out";
        $smtpSumZagSumm = $this->em->getConnection()->prepare($SQLSumZagSumm);
        $smtpSumZagSumm->execute();
        $arrayResult = $smtpSumZagSumm->fetchAll();
        $this->assertEquals("30218.14", $arrayResult[0]['sum(zag_summ)']);
        $this->deleteAllFromReestrOut();

    }

    /**
     * Проверка результатов  загрузки данных из файла testDataСorrectReestrIn_TAB1 два раза подряд
     * @param $logName
     */
    private function validDoubleLoadCorrectReestrOut($logName): void
    {
        $arrayLog = $this->getFileLogToArray(__DIR__ . '\\dirForMoveFilesWithError\\' . $logName);
        $this->assertLogContext($arrayLog);
        $this->assertFileExists(__DIR__ . '\\dirForMoveFilesWithError\\testDataСorrectReestrOut_TAB2.xls');
        $this->assertFileExists(__DIR__ . '\\dirForMoveFilesWithError\\testDataСorrectReestrOut_TAB2.log');
    }

    /**
     * Перенос файлов с dirForMoveFiles в fixturesFiles
     * @param string $fileName
     * @param string $logName
     */
    private function moveFileToFixtures(string $fileName,string $logName): void
    {
        // вернем файлы обратно
        workWithFiles::moveFiles(
            __DIR__ . '\\dirForMoveFiles\\'.$fileName,
            __DIR__ . '\\fixturesFiles');
        if (file_exists(__DIR__ . '\\dirForMoveFilesWithError\\'.$logName)) {
            unlink(__DIR__ . '\\dirForMoveFilesWithError\\'.$logName);
        }
    }

    /**
     * Принудительное удаление всех даннных их таблицы reestrbranch_out
     * @throws \Doctrine\DBAL\DBALException
     */
    private function deleteAllFromReestrOut(): void
    {
        // очистим таблицу с данными
        $SQLDeleteRec = "DELETE  FROM reestrbranch_out";
        $smtpDeleteRec = $this->em->getConnection()->prepare($SQLDeleteRec);
        $smtpDeleteRec->execute();
    }

    /**
     * Получение содержимого файла как массив строк
     * @param string $fileName
     * @return array
     */
    private function getFileLogToArray(string $fileName):array {
        return file($fileName);
    }

    /**
     * проверка массива на правильность ошибок
     * @param $arrayLog
     */
    private function assertLogContext($arrayLog): void
    {
        $this->assertEquals(
            'Строка № 2 содержит ошибки =>> Филиал уже подавал РПН за этот период ранее |',
            trim($arrayLog[0]),
            "Элемент arrayLog с индексом 0 содержи ошибки ");
    }




}
