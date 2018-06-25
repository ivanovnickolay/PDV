<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 16.06.2018
 * Time: 14:16
 */

namespace App\Services;

use App\Utilits\loadDataExcel\cacheDataRow\cacheDataRow;
use App\Utilits\workToFileSystem\workWithFiles;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/*
 * Тестирование на загрузку больших файлов
 * testDataLargeFileReestrOut_TAB2.xlsx
 *   -  филиал 586
 *   -  за 08-2017
 *   -  10032 записи
 *   -  сума ПДВ 95137597,80 грн
 * testDataLargeFileErrorReestrIn_TAB1.xlsx
 *  -   филиал 667
 *  -   период 08-2017
 *  -   13258 записей
 *  -   сума ПДВ 12427792,37
 *  -   Ошибки при валидации РКЕ
 *
 * testDataLargeFileReestrIn_TAB1.xlsx
 *  -   филиал 678
 *  -   период 08-2017
 *  -   5514 записей
 *  -   сума ПДВ 9964593,54
 *  -   Ошибки при валидации РКЕ
 */
class LoadReestrFromLargeFilesWithCacheTest extends KernelTestCase
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
        // подготавливаем файлы
        $this->prepareFileForLoad(
            "testDataLargeFileReestrOut_TAB2.xlsx");
        $this->prepareFileForLoad(
            "testDataLargeFileErrorReestrIn_TAB1.xlsx");
        $this->prepareFileForLoad(
            "testDataLargeFileReestrIn_TAB1.xlsx");


    }

    /*
        * @throws \Doctrine\DBAL\DBALException
        */
    public function tearDown(){
        // удаляем загруженное из базы
        try {
            $this->deleteAllFromReestrIn();
        } catch (DBALException $e) {
        }
        try {
            $this->deleteAllFromReestrOut();
        } catch (DBALException $e) {
        }

        // возвращаем файлы обратно

        $this->moveCorrectFileToFixtures(
            "testDataLargeFileReestrOut_TAB2.xlsx");
        $this->moveCorrectFileToFixtures(
            "testDataLargeFileReestrIn_TAB1.xlsx");

        workWithFiles::moveFiles(
            __DIR__ . '\\dirForMoveFilesWithError\\testDataLargeFileErrorReestrIn_TAB1.xlsx',
            __DIR__ . '\\fixturesFiles');
        workWithFiles::moveFiles(
            __DIR__ . '\\dirForMoveFilesWithError\\testDataLargeFileErrorReestrIn_TAB1.log',
            __DIR__ . '\\fixturesFiles');

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
     * подготовка файлов с даннми для загрузки
     * @param string $fileName
     * @param string $logName
     */
    private function prepareFileForLoad(string $fileName): void
    {
        // подготовим файлы для загрузки
        workWithFiles::moveFiles(
            __DIR__ . '\\fixturesFiles\\'.$fileName,
            __DIR__ . '\\dirForLoadFiles');

    }

    /**
     * Перенос файлов с dirForMoveFiles в fixturesFiles
     * @param string $fileName
     */
    private function moveCorrectFileToFixtures(string $fileName): void
    {
        // вернем файлы обратно
        workWithFiles::moveFiles(
            __DIR__ . '\\dirForMoveFiles\\'.$fileName,
            __DIR__ . '\\fixturesFiles');
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
     * Проверка результатов корректной загрузки данных из файла testDataLargeFileReestrIn_TAB1.xlsx
     * @throws \Doctrine\DBAL\DBALException
     */
    private function validCorrectLoadReestrIn(): void
    {
        $this->assertFileNotExists(__DIR__ . '\\dirForMoveFilesWithError\testDataLargeFileReestrIn_TAB1.xlsx');
        $this->assertFileExists(__DIR__ . '\\dirForMoveFiles\testDataLargeFileReestrIn_TAB1.xlsx');
        // Проведем проверку что загрузилось
        // контроль количества записей
        $SQLCountRec = "SELECT COUNT(id) FROM reestrbranch_in";
        $smtpCountRec = $this->em->getConnection()->prepare($SQLCountRec);
        $smtpCountRec->execute();
        $arrayResult = $smtpCountRec->fetchAll();
        $this->assertEquals(5514, $arrayResult[0]['COUNT(id)']);
        // контроль общей загруженной суммы всех документов
        $SQLSumZagSumm = "SELECT sum(zag_summ) FROM reestrbranch_in";
        $smtpSumZagSumm = $this->em->getConnection()->prepare($SQLSumZagSumm);
        $smtpSumZagSumm->execute();
        $arrayResult = $smtpSumZagSumm->fetchAll();
        $this->assertEquals("60051618.02", $arrayResult[0]['sum(zag_summ)']);
    }

    /**
     * Проверка результатов корректной загрузки данных из файла testDataLargeFileReestrOut_TAB2.xlsx
     * @throws \Doctrine\DBAL\DBALException
     */
    private function validCorrectLoadReestrOut(): void
    {
        $this->assertFileNotExists(__DIR__ . '\\dirForMoveFilesWithError\testDataLargeFileReestrOut_TAB2.xlsx');
        $this->assertFileExists(__DIR__ . '\\dirForMoveFiles\testDataLargeFileReestrOut_TAB2.xlsx');
        // Проведем проверку что загрузилось
        // контроль количества записей
        $SQLCountRec = "SELECT COUNT(id) FROM reestrbranch_out";
        $smtpCountRec = $this->em->getConnection()->prepare($SQLCountRec);
        $smtpCountRec->execute();
        $arrayResult = $smtpCountRec->fetchAll();
        $this->assertEquals(10032, $arrayResult[0]['COUNT(id)']);
        // контроль общей загруженной суммы всех документов
        $SQLSumZagSumm = "SELECT sum(zag_summ) FROM reestrbranch_out";
        $smtpSumZagSumm = $this->em->getConnection()->prepare($SQLSumZagSumm);
        $smtpSumZagSumm->execute();
        $arrayResult = $smtpSumZagSumm->fetchAll();
        $this->assertEquals("2123266185.78", $arrayResult[0]['sum(zag_summ)']);
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
     * проверка лога ошибок testDataLargeFileReestrIn_TAB1.log
     * @param string $logName
     */
    private function validErrorValidReestrIn(string $logName){
        $this->assertFileExists(__DIR__.'\\dirForMoveFilesWithError\\'.$logName);
        $arrayLog=$this->getFileLogToArray(__DIR__.'\\dirForMoveFilesWithError\\'.$logName);

        $this->assertEquals(
            'Строка № 182 содержит ошибки =>> Указан не верные реквизиты документа который корректировал РКЕ |  |',
            trim($arrayLog[0]),
            "Элемент arrayLog с индексом 0 содержи ошибки ");
        }

    public function test_EntityManager(){
        $this->assertInstanceOf(EntityManager::class,$this->em);
    }

    /**
     * Тест на чтение больших файлов
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws DBALException
     */
    public function test_loadLargeFiles(){
        $this->loadReestrFromFile();
            $this->validCorrectLoadReestrIn();
                $this->validCorrectLoadReestrOut();
                    $this->validErrorValidReestrIn('testDataLargeFileErrorReestrIn_TAB1.log');
    }


}
