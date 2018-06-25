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
class handlerRowsValidTest_DataFromFile_CorrectDataCorrectDataReestrIn_Test extends KernelTestCase
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
                ["month"=>12,"year"=>2016,"numBranch"=>"578"],
                null,
                1
            ),
            array(
                array('month'=>12,'year'=>2016,'numBranch'=>"616"),
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
        $objectManager = $this->createMock(EntityManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($mapReestr));


        $configLoader = new configLoadReestrIn();
        $fileName= __DIR__.'\\testDataСorrectReestrIn_TAB1.xls';
        //$this->reader= new getReaderExcel('C:\OSPanel\domains\PDV_UZ\tests\Utilits\loadDataExcel\createEntityForLoad\entityForLoad\testDataReestrIn_TAB1.xls');
            $this->reader= new getReaderExcel($fileName);
            $this->reader->createFilter($configLoader->getLastColumn());
        $this->reader->getReader();
       // $this->entity = $configLoader->getEntityForLoad();
        $this->handlerRow = new handlerRowsValid($objectManager,$configLoader);
 }
    }
    public function test_validRow_1_CorrectRow(){
            $this->reader->loadDataFromFileWithFilter(2);
            $arr=$this->reader->getRowDataArray(2);
            $this->handlerRow->handlerRow($arr);
            $arrayError = $this->handlerRow->getResultHandlingAllRows();
            $this->assertEmpty($arrayError, 'Массив вернулся пустым ');
            //var_dump($arrayError);
    }
    public function test_validRow_2_CorrectRow(){
        $this->reader->loadDataFromFileWithFilter(2);
        $arr=$this->reader->getRowDataArray(3);
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        $this->assertEmpty($arrayError, 'Массив вернулся пустым ');
        //var_dump($arrayError);
    }
    public function test_validRow_3_CorrectRow(){
        $this->reader->loadDataFromFileWithFilter(2);
        $arr=$this->reader->getRowDataArray(4);
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        $this->assertEmpty($arrayError, 'Массив вернулся пустым ');
        //var_dump($arrayError);
    }
    public function test_validRow_4_CorrectRow(){
        $this->reader->loadDataFromFileWithFilter(2);
        $arr=$this->reader->getRowDataArray(5);
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        $this->assertEmpty($arrayError, 'Массив вернулся пустым ');
        //var_dump($arrayError);
    }
    public function test_validRow_5_CorrectRow(){
        $this->reader->loadDataFromFileWithFilter(2);
        $arr=$this->reader->getRowDataArray(6);
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        $this->assertEmpty($arrayError, 'Массив вернулся пустым ');
        //var_dump($arrayError);
    }
    public function test_validRow_6_CorrectRow(){
        $this->reader->loadDataFromFileWithFilter(2);
        $arr=$this->reader->getRowDataArray(7);
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        $this->assertEmpty($arrayError, 'Массив вернулся пустым ');
        //var_dump($arrayError);
    }

    public function test_validRow_1_2_CorrectRow(){
        $this->reader->loadDataFromFileWithFilter(2);
        $arr2=$this->reader->getRowDataArray(2);
        $this->handlerRow->handlerRow($arr2);
        $arr3=$this->reader->getRowDataArray(3);
        $this->handlerRow->handlerRow($arr3);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        $this->assertEmpty($arrayError, 'Массив вернулся пустым ');
        //var_dump($arrayError);
    }
}
