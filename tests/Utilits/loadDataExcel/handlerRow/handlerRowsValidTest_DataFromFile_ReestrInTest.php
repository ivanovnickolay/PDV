<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 07.02.2018
 * Time: 22:11
 */

namespace App\Utilits\loadDataExcel\handlerRow;

use App\Entity\ReestrbranchIn;
use App\Entity\Repository\ReestrBranch_in;
use App\Entity\SprBranch;
use App\Utilits\loadDataExcel\configLoader\configLoadReestrIn;
use App\Utilits\loadDataExcel\createReaderFile\getReaderExcel;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Тестирование функциональности класса handlerRowsValid при проверке РПН кредита.
 * При помощи моков делаем подмену методов и проводим тестирование
 * путем считываения строк их файла с данными. Таким образом дополнительно тестируется
 * классы
 *  - getReaderExcel
 *  - configLoadReestrIn
 *
 *
 * Class handlerRowsValidTest
 * @package App\Utilits\loadDataExcel\handlerRow
 */
class handlerRowsValidTestDataFromFile_ReestrIn_Test extends KernelTestCase
{
    /**
     * @var getReaderExcel
     */
    private $reader;
    /**
     * @var handlerRowsValid
     */
    private $handlerRow;

    /**
     * перед каждым вызовом теста создаем ридер
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     */
    public function setUp(){
        // настроим моки для проверки валидации из базы
        // public function findOneBy(array $criteria, array $orderBy = null)
        // @see https://exceptionshub.com/phpunits-returnvaluemap-not-yielding-expected-results.html
        {
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
        $objectManager = $this->createMock(EntityManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($mapReestr));


        $configLoader = new configLoadReestrIn();
        $fileName= __DIR__.'\\testDataReestrIn_TAB1.xls';
        //$this->reader= new getReaderExcel('C:\OSPanel\domains\PDV_UZ\tests\Utilits\loadDataExcel\createEntityForLoad\entityForLoad\testDataReestrIn_TAB1.xls');
            $this->reader= new getReaderExcel($fileName);
            $this->reader->createFilter($configLoader->getLastColumn());
        $this->reader->getReader();
       // $this->entity = $configLoader->getEntityForLoad();
        $this->handlerRow = new handlerRowsValid($objectManager,$configLoader);
 }
    }

    /**
     * Тестируется первая строка файла с данными
     * по умолчанию и учитывая значения переданныы в Мок должно вернутся сообщение об ошибке
     * что данный данный РПН уже вносился
     * филиалу разрешено передавать данные в ЦФ - этой ошибки не будет
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function test_validRow_2(){
        $this->reader->loadDataFromFileWithFilter(2);
        $arr=$this->reader->getRowDataArray(2);
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        // ошибка должна быть
        $this->assertEquals(
            1,
            substr_count($arrayError[2],"Филиал уже подавал РПН за этот период ранее")
        );
        // ошибки быть не должно
        $this->assertEquals(
            0,
            substr_count($arrayError[2],"Филиал не имеет право подавать РПН на уровень ЦФ")
        );

    }
    /**
     * Тестируется вторая строка файла с данными
     * по умолчанию и учитывая значения переданныы в Мок должно вернутся сообщение об ошибке
     *
     * филиалу НЕ разрешено передавать данные в ЦФ
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function test_validRow_3(){
        $this->reader->loadDataFromFileWithFilter(2);
        $arr=$this->reader->getRowDataArray(3);
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        // ошибки быть не должно
        $this->assertEquals(
            0,
            substr_count($arrayError[2],"Филиал уже подавал РПН за этот период ранее")
        );
        // ошибка должна быть
        $this->assertEquals(
            1,
            substr_count($arrayError[2],"Филиал не имеет право подавать РПН на уровень ЦФ")
        );
        // ошибка должна быть
        $this->assertEquals(
            1,
            substr_count($arrayError[2],"Дата создания документа null не может быть пустым")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[2],"Дата получения документа null не может быть пустым")
        );


    }
    /**
     * Тестируется третья строка файла с данными
     * по умолчанию и учитывая значения переданныы в Мок должно вернутся сообщение об ошибке
     *
     * филиалу НЕ разрешено передавать данные в ЦФ
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function test_validRow_4(){
        $this->reader->loadDataFromFileWithFilter(2);
        $arr=$this->reader->getRowDataArray(4);
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        $this->assertNotEmpty($arrayError, 'Массив вернулся пустым ');
        //var_dump($arrayError);
        // ошибка должна быть
        $this->assertEquals(
            1,
            substr_count($arrayError[2],"Филиал уже подавал РПН за этот период ранее")
        );
        // ошибка должна быть
        $this->assertEquals(
            0,
            substr_count($arrayError[2],"Филиал не имеет право подавать РПН на уровень ЦФ")
        );
        // ошибка должна быть
        $this->assertEquals(
            0,
            substr_count($arrayError[2],"Дата создания документа null не может быть пустым")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[2],"не верный номер документа ")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[2],"ИНН документа не может быть пустым ")
        );
        /*
        $this->assertEquals(
            1,
            substr_count($arrayError[2],"должен содержать только цифры")
        );*/

        //
    }
    public function test_validRow_5(){
        $this->reader->loadDataFromFileWithFilter(2);
        $arr=$this->reader->getRowDataArray(5);
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        $this->assertNotEmpty($arrayError, 'Массив вернулся пустым ');
        //var_dump($arrayError);
        // ошибка должна быть
        $this->assertEquals(
            1,
            substr_count($arrayError[2],"Филиал уже подавал РПН за этот период ранее")
        );
        // ошибка должна быть
        $this->assertEquals(
            0,
            substr_count($arrayError[2],"Филиал не имеет право подавать РПН на уровень ЦФ")
        );
        // ошибка должна быть
        $this->assertEquals(
            0,
            substr_count($arrayError[2],"Дата создания документа null не может быть пустым")
        );
        $this->assertEquals(
            0,
            substr_count($arrayError[2],"не верный номер документа ")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[2],"Номер документа null не может быть пустым ")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[2],"ИНН документа не может быть пустым ")
        );
        //
    }
    public function test_validRow_6()
    {
        $this->reader->loadDataFromFileWithFilter(2);
        $arr = $this->reader->getRowDataArray(6);
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        $this->assertNotEmpty($arrayError, 'Массив вернулся пустым ');
        //var_dump($arrayError);
        // ошибка должна быть
        $this->assertEquals(
            1,
            substr_count($arrayError[2], "Филиал уже подавал РПН за этот период ранее")
        );
        // ошибка должна быть
        $this->assertEquals(
            0,
            substr_count($arrayError[2], "Филиал не имеет право подавать РПН на уровень ЦФ")
        );
        // ошибка должна быть
        $this->assertEquals(
            0,
            substr_count($arrayError[2], "Дата создания документа null не может быть пустым")
        );
        $this->assertEquals(
            0,
            substr_count($arrayError[2], "не верный номер документа ")
        );
        $this->assertEquals(
            0,
            substr_count($arrayError[2], "Номер документа null не может быть пустым ")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[2], "Поле zagSumm \"лрлрло\" содержит данные не того типа.")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[2], "Поле baza7 \"рлрл\" содержит данные не того типа. ")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[2], " Поле pdv7 \"_8888\" содержит данные не того типа. ")
        );
        //
    }



}
