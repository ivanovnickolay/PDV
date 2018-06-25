<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13.06.2018
 * Time: 00:31
 */

namespace App\Services;

use App\Utilits\workToFileSystem\workWithFiles;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/*
 * Тест на чтение нескольких файлов с проверкой в базе данных и по содержимому логов с ошибками
 */
class LoadReestrFromManyFilesTest extends KernelTestCase
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
            "testDataReestrIn_TAB1.xls",
            "testDataReestrIn_TAB1.log");
            $this->prepareFileForLoad(
            "testDataReestrOut_TAB2.xlsx",
            "testDataReestrOut_TAB2.log");
                $this->prepareFileForLoad(
            "testDataСorrectReestrIn_TAB1.xls",
            "testDataСorrectReestrIn_TAB1.log");
                    $this->prepareFileForLoad(
            "testDataСorrectReestrOut_TAB2.xls",
            "testDataСorrectReestrOut_TAB2.log");

    }

    public function test_EntityManager(){
        $this->assertInstanceOf(EntityManager::class,$this->em);
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
        $obj->execute();
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
     * Перенос файлов с dirForMoveFilesWithError в fixturesFiles
     * @param string $fileName
     * @param string $logName
     */
    private function moveErrorFileToFixtures(string $fileName, string $logName): void
    {
        workWithFiles::moveFiles(
            __DIR__.'\\dirForMoveFilesWithError\\'.$fileName,
            __DIR__.'\\fixturesFiles');
        if (file_exists(__DIR__.'\\dirForMoveFilesWithError\\'.$logName)) {
            unlink(__DIR__ . '\\dirForMoveFilesWithError\\'.$logName);
        }
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
     * Получение содержимого файла как массив строк
     * @param string $fileName
     * @return array
     */
    private function getFileLogToArray(string $fileName):array {
        return file($fileName);
    }

    /**
     * Проверка результатов корректной загрузки данных из файла testDataСorrectReestrIn_TAB1
     * @throws \Doctrine\DBAL\DBALException
     */
    private function validCorrectLoadReestrIn(): void
    {
        $this->assertFileNotExists(__DIR__ . '\\dirForMoveFilesWithError\testDataСorrectReestrIn_TAB1.xls');
        $this->assertFileExists(__DIR__ . '\\dirForMoveFiles\testDataСorrectReestrIn_TAB1.xls');
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
        $this->deleteAllFromReestrIn();

    }

    /**
     * проверка лога ошибок testDataReestrIn_TAB1.log
     * @param string $logName
     */
    private function validErrorValidReestrIn(string $logName){
        $arrayLog=$this->getFileLogToArray(__DIR__.'\\dirForMoveFilesWithError\\'.$logName);

        $this->assertEquals(
            'Строка № 3 содержит ошибки =>> Номер филиала реестра не соответствует номеру указанному в первой строке файла! Дата получения документа null не может быть пустым  | Дата создания документа null не может быть пустым  |  |',
            trim($arrayLog[0]),
            "Элемент arrayLog с индексом 1 содержи ошибки ");
        $this->assertEquals(
            'Строка № 4 содержит ошибки =>> пп - не верный номер документа  | ИНН документа не может быть пустым  |  |',
            trim($arrayLog[1]),
            "Элемент arrayLog с индексом 2 содержи ошибки ");
        $this->assertEquals(
            'Строка № 5 содержит ошибки =>> Номер документа null не может быть пустым  | ИНН документа не может быть пустым  |  |',
            trim($arrayLog[2]),
            "Элемент arrayLog с индексом 3 содержи ошибки ");
        $this->assertEquals(
            'Строка № 6 содержит ошибки =>> Поле zagSumm "лрлрло" содержит данные не того типа. | Поле baza7 "рлрл" содержит данные не того типа. | Поле pdv7 "_8888" содержит данные не того типа. |  |',
            trim($arrayLog[3]),
            "Элемент arrayLog с индексом 4 содержи ошибки ");
        $this->assertEquals(
            'Строка № 7 содержит ошибки =>> Указан не верные реквизиты документа который корректировал РКЕ |  |',
            trim($arrayLog[4]),
            "Элемент arrayLog с индексом 5 содержи ошибки ");
    }

    /**
     * проверка лога ошибок testDataReestrOut_TAB2.log
     * @param string $logName
     */
    private function validErrorValidReestrOut(string $logName){
        $arrayLog=$this->getFileLogToArray(__DIR__.'\\dirForMoveFilesWithError\\'.$logName);

        $this->assertEquals(
            'Строка № 3 содержит ошибки =>> 44рдрлд - не верный номер документа  | Указан  не верный тип причины не выдачи документа покупателю |  |',
            trim($arrayLog[0]),
            "Элемент arrayLog с индексом 1 содержит ошибки ");
        $this->assertEquals(
            'Строка № 4 содержит ошибки =>> опо//275 - не верный номер документа  | Указан "ПНП" не верный тип документа | Указан  не верный тип причины не выдачи документа покупателю |  |',
            trim($arrayLog[1]),
            "Элемент arrayLog с индексом 2 содержит ошибки ");
        $this->assertEquals(
            'Строка № 5 содержит ошибки =>> Дата создания документа null не может быть пустым  | Поле zagSumm "ллплпл" содержит данные не того типа. | Указан не верные реквизиты документа который корректировал РКЕ |  |',
            trim($arrayLog[2]),
            "Элемент arrayLog с индексом 3 содержит ошибки ");
        $this->assertEquals(
            'Строка № 6 содержит ошибки =>> Месяц реестра не соответствует месяцу указанному в первой строке файла! Год реестра не соответствует году указанному в первой строке файла! Значение месяца 14 больше 12 | Значение года больше 2020 | Дата создания документа null не может быть пустым  | Поле zagSumm "ллплпл" содержит данные не того типа. | Указан не верные реквизиты документа который корректировал РКЕ |  |',
            trim($arrayLog[3]),
            "Элемент arrayLog с индексом 4 содержит ошибки ");
        $this->assertEquals(
            'Строка № 7 содержит ошибки =>> Месяц реестра не соответствует месяцу указанному в первой строке файла! Год реестра не соответствует году указанному в первой строке файла! Значение месяца -14 меньше 1 | Значение года меньше 2015 | Дата создания документа null не может быть пустым  | Поле zagSumm "ллплпл" содержит данные не того типа. | Указан не верные реквизиты документа который корректировал РКЕ |  |',
            trim($arrayLog[4]),
            "Элемент arrayLog с индексом 5 содержит ошибки ");
        $this->assertEquals(
            'Строка № 8 содержит ошибки =>> Месяц реестра не соответствует месяцу указанному в первой строке файла! Год реестра не соответствует году указанному в первой строке файла! Номер филиала реестра не соответствует номеру указанному в первой строке файла! Значение месяца -14 меньше 1 | Значение года меньше 2015 | Номер структурного подразделения " " должен содержать только цифры . | This value should have exactly 3 characters. | Дата создания документа null не может быть пустым  | Поле zagSumm "ллплпл" содержит данные не того типа. | Указан не верные реквизиты документа который корректировал РКЕ |  |',
            trim($arrayLog[5]),
            "Элемент arrayLog с индексом 6 содержит ошибки ");
        $this->assertEquals(
            'Строка № 9 содержит ошибки =>> Поле zagSumm "пвав" содержит данные не того типа. | Поле baza20 "апу" содержит данные не того типа. | Поле pdv20 "вап" содержит данные не того типа. | Поле baza7 "вап" содержит данные не того типа. | Поле pdv7 "вп" содержит данные не того типа. | Поле baza0 "вп" содержит данные не того типа. |  |',
            trim($arrayLog[6]),
            "Элемент arrayLog с индексом 5 содержит ошибки ");

    }

    /**
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function test_LoadFromManyFiles()
    {
        // загружаем файлы
            $this->loadReestrFromFile();
            // проверяем на корректность записи в базу
            $this->validCorrectLoadReestrIn();
                $this->validCorrectLoadReestrOut();
                // проверяем наличие и содержимое логов с ошибками валидации
                $this->validErrorValidReestrIn("testDataReestrIn_TAB1.log");
                    $this->validErrorValidReestrOut("testDataReestrOut_TAB2.log");
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
            $this->moveErrorFileToFixtures(
                "testDataReestrIn_TAB1.xls",
                "testDataReestrIn_TAB1.log");
            $this->moveErrorFileToFixtures(
                "testDataReestrOut_TAB2.xlsx",
                "testDataReestrOut_TAB2.log");
            $this->moveCorrectFileToFixtures(
                "testDataСorrectReestrIn_TAB1.xls");
            $this->moveCorrectFileToFixtures(
                "testDataСorrectReestrOut_TAB2.xls");
        }

}
