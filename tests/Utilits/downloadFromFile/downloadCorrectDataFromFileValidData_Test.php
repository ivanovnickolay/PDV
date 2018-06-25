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
 * загрузка корректных файлов
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
class downloadCorrectDataFromFileValidData_Test extends TestCase
{

    /**
     * @var
     */
    private $objectManager;

    public function setUp()
    {
        $this->objectManager = $this->createMock(EntityManager::class);
    }
   /**
     * тест на проверку валидации файлов ReestrIn
     * перед валидацией необходимо настроить обманку на doctrine
     */
    public function test_downloadDataAndValid_ReestrIn(){
        $mapIn = array(
            array(
                ["month"=>12,"year"=>2016,"numBranch"=>"578"],
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
        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($mapReestr));

        $obj = new downloadFromFile($this->objectManager);

        // @link http://php.net/manual/ru/language.constants.predefined.php
        $fileName = __DIR__."\\testDataСorrectReestrIn_TAB1.xls";
        $obj->setFileName($fileName);
            $arrayError = $obj->downloadDataAndValid();
            // по строчно проверяем ошибки
               $this->assertEmpty($arrayError);
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
