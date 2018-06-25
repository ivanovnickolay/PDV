<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28.05.2018
 * Time: 15:46
 */

namespace App\Services;

use App\Entity\ReestrbranchOut;
use App\Entity\Repository\ReestrBranch_out;
use App\Entity\SprBranch;
use App\Utilits\loadDataExcel\downloadFromFile;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use App\Utilits\workToFileSystem\workWithFiles;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\{
    TestCase
};

class LoadReestrFromFileReestrOutValidDataTest extends TestCase
{
    private $objectManager;

    public function setUp(){
        $mapIn = array(
            array(
                ["month" => 7, "year" => 2016, "numBranch" => "578"],
                null,
                1
            ),
            array(
                array('month' => 7, 'year' => 2016, 'numBranch' => "579"),
                null,
                null
            ),
            array(
                array('month' => 12, 'year' => 2016, 'numBranch' => "655"),
                null,
                null
            ),
        );
        $repoReestrIn = $this->createMock(ReestrBranch_out::class);
        $repoReestrIn->expects($this->any())
            ->method("findOneBy")
            ->will($this->returnValueMap($mapIn));

        $mapSpr = array(
            array(
                array('numMainBranch' => '578'),
                null,
                10
            ),
            array(
                array('numMainBranch' => '655'),
                null,
                10
            ),
            array(
                array('numMainBranch' => '579'),
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
            array(ReestrbranchOut::class, $repoReestrIn)
        );
        $this->objectManager = $this->createMock(EntityManager::class);
        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($mapReestr));
    }
    /**
     * Чтение файла с ошибками
     * testDataReestrOut_TAB2.xlsx
     * @throws \ReflectionException
     * @throws errorLoadDataException
     */

    public function test_validDataToFile(){
        // подготовим файл для загрузки
        workWithFiles::moveFiles(
            __DIR__.'\\fixturesFiles\\testDataReestrOut_TAB2.xlsx',
            __DIR__.'\\dirForLoadFiles');
        if (file_exists(__DIR__.'\\dirForMoveFilesWithError\\testDataReestrOut_TAB2.log')) {
            unlink(__DIR__ . '\\dirForMoveFilesWithError\\testDataReestrOut_TAB2.log');
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
        $download->setFileName(__DIR__.'\\dirForLoadFiles\\testDataReestrOut_TAB2.xlsx');
        // проверяем работу "публичного метода
        $resTest = $method->invoke($obj,$download,__DIR__.'\\dirForLoadFiles\\testDataReestrOut_TAB2.xlsx');
        $this->assertEquals(false,$resTest );
        // проверяем внутреннюю работу "публичного" метода
        $this->assertFileExists(
            __DIR__.'\\dirForMoveFilesWithError\\testDataReestrOut_TAB2.xlsx',
            "Файл c данными не найден !");
        $this->assertFileExists(
            __DIR__.'\\dirForMoveFilesWithError\\testDataReestrOut_TAB2.log',
            "Файл c логами не найден !");
                $arrayLog = $this->getFileLogToArray(__DIR__ . '\\dirForMoveFilesWithError\\testDataReestrOut_TAB2.log');
                    $this->assertLogContext($arrayLog);
        // вернем файл обратно
        workWithFiles::moveFiles(
            __DIR__.'\\dirForMoveFilesWithError\\testDataReestrOut_TAB2.xlsx',
            __DIR__.'\\fixturesFiles');
        if (file_exists(__DIR__.'\\dirForMoveFilesWithError\\testDataReestrOut_TAB2.log')) {
            unlink(__DIR__ . '\\dirForMoveFilesWithError\\testDataReestrOut_TAB2.log');
        }
    }

    public function test_validDataToFileCorrect(){
        // подготовим файл для загрузки
        workWithFiles::moveFiles(
            __DIR__.'\\fixturesFiles\\testDataСorrectReestrOut_TAB2.xls',
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
        $download->setFileName(__DIR__.'\\dirForLoadFiles\\testDataСorrectReestrOut_TAB2.xls');
        // проверяем работу "публичного метода
        $resTest = $method->invoke($obj,$download,__DIR__.'\\dirForLoadFiles\\testDataСorrectReestrOut_TAB2.xls');
        // ошибок быть не должно - массив ДОЛЖЕН быть пустым
        $this->assertEquals(true,$resTest );
        // вернем файл обратно
        workWithFiles::moveFiles(
            __DIR__.'\\dirForLoadFiles\\testDataСorrectReestrOut_TAB2.xls',
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
     * проверка содержимого массива с ошибками с эталоном
     * @param $arrayLog
     */
    private function assertLogContext($arrayLog): void
    {
        $this->assertEquals(
            'Строка № 2 содержит ошибки =>> Филиал не имеет право подавать РПН на уровень ЦФ |',
            trim($arrayLog[0]),
            "Элемент arrayLog с индексом 0 содержит ошибки ");
        $this->assertEquals(
            'Строка № 3 содержит ошибки =>> 44рдрлд - не верный номер документа  | Указан  не верный тип причины не выдачи документа покупателю |  |',
            trim($arrayLog[1]),
            "Элемент arrayLog с индексом 1 содержит ошибки ");
        $this->assertEquals(
            'Строка № 4 содержит ошибки =>> опо//275 - не верный номер документа  | Указан "ПНП" не верный тип документа | Указан  не верный тип причины не выдачи документа покупателю |  |',
            trim($arrayLog[2]),
            "Элемент arrayLog с индексом 2 содержит ошибки ");
        $this->assertEquals(
            'Строка № 5 содержит ошибки =>> Дата создания документа null не может быть пустым  | Поле zagSumm "ллплпл" содержит данные не того типа. | Указан не верные реквизиты документа который корректировал РКЕ |  |',
            trim($arrayLog[3]),
            "Элемент arrayLog с индексом 3 содержит ошибки ");
        $this->assertEquals(
            'Строка № 6 содержит ошибки =>> Месяц реестра не соответствует месяцу указанному в первой строке файла! Год реестра не соответствует году указанному в первой строке файла! Значение месяца 14 больше 12 | Значение года больше 2020 | Дата создания документа null не может быть пустым  | Поле zagSumm "ллплпл" содержит данные не того типа. | Указан не верные реквизиты документа который корректировал РКЕ |  |',
            trim($arrayLog[4]),
            "Элемент arrayLog с индексом 4 содержит ошибки ");
        $this->assertEquals(
            'Строка № 7 содержит ошибки =>> Месяц реестра не соответствует месяцу указанному в первой строке файла! Год реестра не соответствует году указанному в первой строке файла! Значение месяца -14 меньше 1 | Значение года меньше 2015 | Дата создания документа null не может быть пустым  | Поле zagSumm "ллплпл" содержит данные не того типа. | Указан не верные реквизиты документа который корректировал РКЕ |  |',
            trim($arrayLog[5]),
            "Элемент arrayLog с индексом 5 содержит ошибки ");
        $this->assertEquals(
            'Строка № 8 содержит ошибки =>> Месяц реестра не соответствует месяцу указанному в первой строке файла! Год реестра не соответствует году указанному в первой строке файла! Номер филиала реестра не соответствует номеру указанному в первой строке файла! Значение месяца -14 меньше 1 | Значение года меньше 2015 | Номер структурного подразделения " " должен содержать только цифры . | This value should have exactly 3 characters. | Дата создания документа null не может быть пустым  | Поле zagSumm "ллплпл" содержит данные не того типа. | Указан не верные реквизиты документа который корректировал РКЕ |  |',
            trim($arrayLog[6]),
            "Элемент arrayLog с индексом 6 содержит ошибки ");
        $this->assertEquals(
            'Строка № 9 содержит ошибки =>> Поле zagSumm "пвав" содержит данные не того типа. | Поле baza20 "апу" содержит данные не того типа. | Поле pdv20 "вап" содержит данные не того типа. | Поле baza7 "вап" содержит данные не того типа. | Поле pdv7 "вп" содержит данные не того типа. | Поле baza0 "вп" содержит данные не того типа. |  |',
            trim($arrayLog[7]),
            "Элемент arrayLog с индексом 5 содержит ошибки ");

    }


}
