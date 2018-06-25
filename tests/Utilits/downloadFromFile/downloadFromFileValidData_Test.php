<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23.05.2018
 * Time: 23:03
 */

namespace App\Utilits\loadDataExcel;

use App\Entity\ReestrbranchIn;
use App\Entity\ReestrbranchOut;
use App\Entity\Repository\ReestrBranch_in;
use App\Entity\Repository\ReestrBranch_out;
use App\Entity\SprBranch;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use Doctrine\ORM\EntityManager;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Tests\Fixtures\Entity;

/**
 * Тест производит интеграционную проверку на корректность
 * валидации данных в классе downloadFromFile которая проводится в
 * public function downloadDataAndValid()
 * загрузка не корректных данных
 *
 * задействованы классы
 *  - handlerRowsValid
 *  - configLoaderFactory::getConfigLoad()
 *  - loadRowsFromFile
 *  - loadRows
 *
 * Class downloadFromFileTest
 * @package App\Utilits\loadDataExcel
 */
class downloadFromFileTest extends TestCase
{

    /**
     * @var
     */
    private $objectManager;

    public function setUp(){
        $this->objectManager = $this->createMock(EntityManager::class);
        // define my virtual file system
        /** @var array $directory */
        $directory=[
            'testFile'=> array(
                'test_tab1.xls',
                'test1___2tab2.xlsx',
                'test1___2tab2fgldghl.xlsx',
                'test1___2TAB2fgldghl.xlsx',
                'test1___2TAB2.xlsx',
                'test1___2TAB1.xlsx',
            )
        ];
        // setup and cache the virtual file system
        $this->file_system = vfsStream::setup('root', 444, $directory);

    }
    /**
     * Тест на создание объекта
     */
    public function test__construct()
    {
        $obj = new downloadFromFile($this->objectManager);
        $this->assertInstanceOf(downloadFromFile::class,$obj,"downloadFromFile not create");
    }

    /**
     * Тест на ошибку
     * @throws errorLoadDataException
     */
    public function test_setFileName_error(){
        $this->expectException(errorLoadDataException::class);
        $this->expectExceptionMessage("Для файла vfs://roottest1___2tab2fgldghl.xls не существует конфигурации для чтения информации из файла");
            $obj = new downloadFromFile($this->objectManager);
                $obj->setFileName($this->file_system->url().'test1___2tab2fgldghl.xls');
    }

    /**
     * Тест на без ошибок
     * @throws errorLoadDataException
     * @link https://github.com/mikey179/vfsStream/issues/98 решение проблемы с file_exists
     */
    public function test_setFileName_Ok(){
        // необходимо чтобы программа принимала файлы обманки и не ругалась при проверке file_exists
        $root = vfsStream::setup();
            $file = vfsStream::newFile('test1___2TAB2.xlsx')->at($root);
                // проверяем удалось ли обмануть систему
                $this->assertTrue(file_exists($file->url()));
                    //$this->expectException(errorLoadDataException::class);
                        //$this->expectExceptionMessage("+++");
                            $obj = new downloadFromFile($this->objectManager);
                $obj->setFileName($file->url());
    }

    /**
     * тест на проверку валидации файлов ReestrIn
     * перед валидацией необходимо настроить обманку на doctrine
     */
    public function test_downloadDataAndValid_ReestrIn(){
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
        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($mapReestr));

        $obj = new downloadFromFile($this->objectManager);

        // @link http://php.net/manual/ru/language.constants.predefined.php
        $fileName = __DIR__."\\testDataReestrIn_TAB1.xls";
        $obj->setFileName($fileName);
            $arrayError = $obj->downloadDataAndValid();
            // по строчно проверяем ошибки
            $this->assertEquals(
                1,
                substr_count($arrayError[2],"Филиал уже подавал РПН за этот период ранее | ")
            );
                $this->assertEquals(
                1,
                substr_count($arrayError[3],"Номер филиала реестра не соответствует номеру указанному в первой строке файла! Дата получения документа null не может быть пустым  | Дата создания документа null не может быть пустым  |  | ")
                );
                    $this->assertEquals(
                        1,
                        substr_count($arrayError[4],"пп - не верный номер документа  | ИНН документа не может быть пустым  |  | ")
                    );
                        $this->assertEquals(
                            1,
                            substr_count($arrayError[5],"Номер документа null не может быть пустым  | ИНН документа не может быть пустым  |  | ")
                        );
                    $this->assertEquals(
                        1,
                        substr_count($arrayError[6],"Поле zagSumm \"лрлрло\" содержит данные не того типа. | Поле baza7 \"рлрл\" содержит данные не того типа. | Поле pdv7 \"_8888\" содержит данные не того типа. |  | ")
                    );
                $this->assertEquals(
                    1,
                    substr_count($arrayError[7],"Указан не верные реквизиты документа который корректировал РКЕ |  | ")
                );
            $obj->unSetAllObjects();
    }
    /**
     * тест на проверку валидации файлов ReestrIn
     * перед валидацией необходимо настроить обманку на doctrine
     */
    public function test_downloadDataAndValid_ReestrOut(){
        $mapOut = array(
            array(
                ["month"=>7,"year"=>2016,"numBranch"=>"678"],
                null,
                1
            ),
            array(
                array('month'=>7,'year'=>2016,'numBranch'=>"677"),
                null,
                null
            ),
        );
        $repoReestrOut = $this->createMock(ReestrBranch_out::class);
        $repoReestrOut->expects($this->any())
            ->method("findOneBy")
            ->will($this->returnValueMap($mapOut));
        $mapSpr=array(
            array(
                array('numMainBranch'=>'578'),
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
            array(ReestrbranchOut::class,$repoReestrOut)
        );
        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($mapReestr));

        $obj = new downloadFromFile($this->objectManager);

        // @link http://php.net/manual/ru/language.constants.predefined.php
        $fileName = __DIR__."\\testDataReestrOut_TAB2.xlsx";
        $obj->setFileName($fileName);
        $arrayError = $obj->downloadDataAndValid();

        // по строчно проверяем ошибки
        $this->assertEquals(
            1,
            substr_count($arrayError[2],"Филиал не имеет право подавать РПН на уровень ЦФ | ")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[3],"44рдрлд - не верный номер документа  | Указан  не верный тип причины не выдачи документа покупателю |  | ")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[4],"опо//275 - не верный номер документа  | Указан \"ПНП\" не верный тип документа | Указан  не верный тип причины не выдачи документа покупателю |  | ")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[5],"Дата создания документа null не может быть пустым  | Поле zagSumm \"ллплпл\" содержит данные не того типа. | Указан не верные реквизиты документа который корректировал РКЕ |  | ")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[6],"Месяц реестра не соответствует месяцу указанному в первой строке файла! Год реестра не соответствует году указанному в первой строке файла! Значение месяца 14 больше 12 | Значение года больше 2020 | Дата создания документа null не может быть пустым  | Поле zagSumm \"ллплпл\" содержит данные не того типа. | Указан не верные реквизиты документа который корректировал РКЕ |  | ")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[7],"Месяц реестра не соответствует месяцу указанному в первой строке файла! Год реестра не соответствует году указанному в первой строке файла! Значение месяца -14 меньше 1 | Значение года меньше 2015 | Дата создания документа null не может быть пустым  | Поле zagSumm \"ллплпл\" содержит данные не того типа. | Указан не верные реквизиты документа который корректировал РКЕ |  | ")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[8],"Месяц реестра не соответствует месяцу указанному в первой строке файла! Год реестра не соответствует году указанному в первой строке файла! Номер филиала реестра не соответствует номеру указанному в первой строке файла! Значение месяца -14 меньше 1 | Значение года меньше 2015 | Номер структурного подразделения \" \" должен содержать только цифры . | This value should have exactly 3 characters. | Дата создания документа null не может быть пустым  | Поле zagSumm \"ллплпл\" содержит данные не того типа. | Указан не верные реквизиты документа который корректировал РКЕ |  | ")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[9],"Поле zagSumm \"пвав\" содержит данные не того типа. | Поле baza20 \"апу\" содержит данные не того типа. | Поле pdv20 \"вап\" содержит данные не того типа. | Поле baza7 \"вап\" содержит данные не того типа. | Поле pdv7 \"вп\" содержит данные не того типа. | Поле baza0 \"вп\" содержит данные не того типа. |  | ")
        );
        $obj->unSetAllObjects();
    }
}
