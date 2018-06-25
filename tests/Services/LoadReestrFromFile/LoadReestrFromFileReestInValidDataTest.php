<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28.05.2018
 * Time: 00:24
 */

namespace App\Services;

use App\Entity\ReestrbranchIn;
use App\Entity\Repository\ReestrBranch_in;
use App\Entity\SprBranch;
use App\Utilits\loadDataExcel\downloadFromFile;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use App\Utilits\workToFileSystem\workWithFiles;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

/**
 *
 * Тестирование валидации файла реестра полученных ПНЕ и всех операция связаных с файлом который не прошел валидацию
 *
 * todo аналогичный тест сделать и для Out
 * Class LoadReestrFromFile_testValiDataTest
 * @package App\Services
 */
class LoadReestrFromFileReestInValidDataTest extends TestCase
{
    private $objectManager;

    public function setUp(){
        // настроим моки для проверки валидации из базы
        // public function findOneBy(array $criteria, array $orderBy = null)
        // @see https://exceptionshub.com/phpunits-returnvaluemap-not-yielding-expected-results.html

            $mapIn = array(
                array(
                    ["month"=>7,"year"=>2016,"numBranch"=>"578"],
                    null,
                    1
                ),
                array(
                    array('month'=>7,'year'=>2016,'numBranch'=>"579"),
                    null,
                    null
                ),
            );
            $repoReestrIn = $this->createMock(ReestrBranch_in::class);
            $repoReestrIn->expects($this->any())
                ->method("findOneBy")
                ->will($this->returnValueMap($mapIn));

            $mapSpr=array(
                array(
                    array('numMainBranch'=>'578'),
                    null,
                    10
                ),
                array(
                    array('numMainBranch'=>'616'),
                    null,
                    10
                ),
                array(
                    array('numMainBranch'=>'579'),
                    null,
                    null
                )
            );
            $repoSpr = $this->createMock(\App\Entity\Repository\SprBranchRepository::class);
            $repoSpr->expects($this->any())
                ->method("findOneBy")
                ->will($this->returnValueMap($mapSpr));
            $mapReestr = array(
                array(SprBranch::class, $repoSpr),
                array(ReestrbranchIn::class,$repoReestrIn)
            );
            $this->objectManager = $this->createMock(EntityManager::class);
            $this->objectManager->expects($this->any())
                ->method('getRepository')
                ->will($this->returnValueMap($mapReestr));
    }


    /**
     * Чтение файла с ошибками
     * testDataReestrIn_TAB1.xls
     * @throws \ReflectionException
     * @throws errorLoadDataException
     */

    public function test_validDataToFile(){
       // подготовим файлы для работы
        workWithFiles::moveFiles(
            __DIR__.'\\fixturesFiles\\testDataReestrIn_TAB1.xls',
            __DIR__.'\\dirForLoadFiles');
        if (file_exists(__DIR__.'\\dirForMoveFilesWithError\\testDataReestrIn_TAB1.log')) {
            unlink(__DIR__ . '\\dirForMoveFilesWithError\\testDataReestrIn_TAB1.log');
        }
        // делаем частный метод публичным в рамках теста
        $method = new \ReflectionMethod(LoadReestrFromFile::class,"validDataToFile");
            $method->setAccessible(true);
                // создаем  объект для тестирования
                $obj = new LoadReestrFromFile($this->objectManager);
                    $obj->setDirForLoadFiles(__DIR__.'\\dirForLoadFiles');
                        $obj->setDirForMoveFiles(__DIR__.'\\dirForMoveFiles');
                            $obj->setDirForMoveFilesWithError(__DIR__.'\\dirForMoveFilesWithError');
                        // создаем необходимые для тестирования "публичного" метода параметры
                        $download = new downloadFromFile($this->objectManager);
                    $download->setFileName(__DIR__.'\\dirForLoadFiles\\testDataReestrIn_TAB1.xls');
                // проверяем работу "публичного метода
                $resTest = $method->invoke($obj,$download,__DIR__.'\\dirForLoadFiles\\testDataReestrIn_TAB1.xls');
                $this->assertEquals(false,$resTest );
                    // проверяем внутреннюю работу "публичного" метода
                    $this->assertFileExists(
                            __DIR__.'\\dirForMoveFilesWithError\\testDataReestrIn_TAB1.xls',
                            "Файл c данными не найден !");
                        $this->assertFileExists(
                                __DIR__.'\\dirForMoveFilesWithError\\testDataReestrIn_TAB1.log',
                                "Файл c логами не найден !");

        $arrayLog=$this->getFileLogToArray(__DIR__.'\\dirForMoveFilesWithError\\testDataReestrIn_TAB1.log');
        $this->assertLogContext($arrayLog);
        // перенесем обратно  файлы в fixturesFiles
        workWithFiles::moveFiles(
            __DIR__.'\\dirForMoveFilesWithError\\testDataReestrIn_TAB1.xls',
            __DIR__.'\\fixturesFiles');
        if (file_exists(__DIR__.'\\dirForMoveFilesWithError\\testDataReestrIn_TAB1.log')) {
            unlink(__DIR__ . '\\dirForMoveFilesWithError\\testDataReestrIn_TAB1.log');
        }

    }

    /**
     * чтение файла без ошибок
     * testDataСorrectReestrIn_TAB1.xls
     * @throws \ReflectionException
     * @throws errorLoadDataException
     */

    public function test_validDataToFileCorrect(){
        // подготовим файлы для работы
            workWithFiles::moveFiles(
                __DIR__.'\\fixturesFiles\\testDataСorrectReestrIn_TAB1.xls',
                __DIR__.'\\dirForLoadFiles');
                    if (file_exists(__DIR__.'\\dirForMoveFilesWithError\\testDataСorrectReestrIn_TAB1.log')) {
                        unlink(__DIR__ . '\\dirForMoveFilesWithError\\testDataСorrectReestrIn_TAB1.log');
                    }

        // делаем частный метод публичным в рамках теста
        $method = new \ReflectionMethod(LoadReestrFromFile::class,"validDataToFile");
        $method->setAccessible(true);
        // создаем  объект для тестирования
        $obj = new LoadReestrFromFile($this->objectManager);
        $obj->setDirForLoadFiles(__DIR__.'\\dirForLoadFiles');
        $obj->setDirForMoveFiles(__DIR__.'\\dirForMoveFiles');
        $obj->setDirForMoveFilesWithError(__DIR__.'\\dirForMoveFilesWithError');
        // создаем необходимые для тестирования "публичного" метода параметры
        $download = new downloadFromFile($this->objectManager);
        $download->setFileName(__DIR__.'\\dirForLoadFiles\\testDataСorrectReestrIn_TAB1.xls');
        // проверяем работу "публичного метода
        $resTest = $method->invoke($obj,$download,__DIR__.'\\dirForLoadFiles\\testDataСorrectReestrIn_TAB1.xls');
        // ошибок быть не должно - массив ДОЛЖЕН быть пустым
        $this->assertEquals(true,$resTest );

        // перенесем обратно  файлы в fixturesFiles
        workWithFiles::moveFiles(
            __DIR__.'\\dirForLoadFiles\\testDataСorrectReestrIn_TAB1.xls',
            __DIR__.'\\fixturesFiles');
        if (file_exists(__DIR__.'\\dirForMoveFilesWithError\\testDataСorrectReestrIn_TAB1.log')) {
            unlink(__DIR__ . '\\dirForMoveFilesWithError\\testDataСorrectReestrIn_TAB1.log');
        }
    }

    /**
     * читает значение файла с логами в массив
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
        $this->assertEquals(
            'Строка № 3 содержит ошибки =>> Номер филиала реестра не соответствует номеру указанному в первой строке файла! Дата получения документа null не может быть пустым  | Дата создания документа null не может быть пустым  |  |',
            trim($arrayLog[1]),
            "Элемент arrayLog с индексом 1 содержи ошибки ");
        $this->assertEquals(
            'Строка № 4 содержит ошибки =>> пп - не верный номер документа  | ИНН документа не может быть пустым  |  |',
            trim($arrayLog[2]),
            "Элемент arrayLog с индексом 2 содержи ошибки ");
        $this->assertEquals(
            'Строка № 5 содержит ошибки =>> Номер документа null не может быть пустым  | ИНН документа не может быть пустым  |  |',
            trim($arrayLog[3]),
            "Элемент arrayLog с индексом 3 содержи ошибки ");
        $this->assertEquals(
            'Строка № 6 содержит ошибки =>> Поле zagSumm "лрлрло" содержит данные не того типа. | Поле baza7 "рлрл" содержит данные не того типа. | Поле pdv7 "_8888" содержит данные не того типа. |  |',
            trim($arrayLog[4]),
            "Элемент arrayLog с индексом 4 содержи ошибки ");
        $this->assertEquals(
            'Строка № 7 содержит ошибки =>> Указан не верные реквизиты документа который корректировал РКЕ |  |',
            trim($arrayLog[5]),
            "Элемент arrayLog с индексом 5 содержи ошибки ");
    }


}
