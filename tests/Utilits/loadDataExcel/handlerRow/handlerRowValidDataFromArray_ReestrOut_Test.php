<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 17.02.2018
 * Time: 21:58
 */

namespace App\Tests\Utilits\handlerRow;


use App\Entity\ReestrbranchOut;
use App\Entity\Repository\ReestrBranch_out;
use App\Entity\SprBranch;
use App\Utilits\loadDataExcel\configLoader\configLoadReestrOut;
use App\Utilits\loadDataExcel\handlerRow\handlerRowsValid;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;


/**
        * Тестирование функциональности класса handlerRowsValid при проверке РПН обязательств.
        * При помощи моков делаем подмену методов и проводим тестирование
        * путем считываения специально подготовленный массив с данными для формирования объекта сущности
        * Таким образом исключается дополнительное тестирование классов
        *  - getReaderExcel
        *  - configLoadReestrIn
        *
 */

class handlerRowValidDataFromArray_ReestrOut_Test extends TestCase
{
    /**
     * @var handlerRowsValid
     */
    private $handlerRow;

    public function setUp()
    {
        // настроим моки для проверки валидации из базы
        // public function findOneBy(array $criteria, array $orderBy = null)
        // @see https://exceptionshub.com/phpunits-returnvaluemap-not-yielding-expected-results.html
        {
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
            $objectManager = $this->createMock(EntityManager::class);
            $objectManager->expects($this->any())
                ->method('getRepository')
                ->will($this->returnValueMap($mapReestr));

            $configLoader = new configLoadReestrOut();
            $this->handlerRow = new handlerRowsValid($objectManager, $configLoader);
        }
    }

    private function getArrayData(): array
    {
        $arr = array();
        //$this->reestrIn=new ReestrbranchIn();
        //$this->reestrIn->setMonth($arr[0][79]);
        $arr[0][79] = 8;
        //$this->reestrIn->setYear($arr[0][87]);
        $arr[0][87] = 2016;
        //$this->reestrIn->setNumBranch($arr[0][66]);
        $arr[0][66] = '578';
        //$this->reestrOut->setDateCreateInvoice($this->getDataType($arr[0][99]));
        $arr[0][99] = "42552";
        //$this->reestrIn->setNumInvoice($arr[0][100]);
        $arr[0][100] = '15';
        //$this->reestrIn->setTypeInvoiceFull($arr[0][121]);
        $arr[0][121] = "ПНЕ";
        //$this->reestrOut->setTypeInvoice($arr[0][119]);
        $arr[0][119] = "";
        //$this->reestrIn->setNameClient($arr[0][103]);
        $arr[0][103] = "Рога и копыта";
        //$this->reestrIn->setInnClient($arr[0][104]);
        $arr[0][104] = "100000000000";
        //$this->reestrIn->setZagSumm($arr[0][106]);
        $arr[0][106] = '12124.22';
        //$this->reestrIn->setBaza20($arr[0][107]);
        $arr[0][107] = "1212.11";
        //$this->reestrIn->setPdv20($arr[0][109]);
        $arr[0][109] = "1213.22";
        //$this->reestrIn->setBaza7($arr[0][108]);
        $arr[0][108] = 0;
        //$this->reestrIn->setPdv7($arr[0][110]);
        $arr[0][110] = 0;
        //$this->reestrIn->setBaza0($arr[0][111]);
        $arr[0][111] = 0;
        //$this->reestrOut->setBazaZvil($arr[0][94]);
        $arr[0][94] = 0;
        //$this->reestrOut->setBazaNeObj($arr[0][97]);
        $arr[0][97] = 0;
        //$this->reestrOut->setBazaZaMezhiTovar($arr[0][95]);
        $arr[0][95] = 0;
        //$this->reestrOut->setBazaZaMezhiPoslug($arr[0][96]);
        $arr[0][96] = 0;
        //$this->reestrIn->setRkeDateCreateInvoice($this->getDataType($arr[0][112]));
        $arr[0][112] = '';
        //$this->reestrIn->setRkeNumInvoice($arr[0][114]);
        $arr[0][114] = '';
        //$this->reestrIn->setRkePidstava($arr[0][115]);
        $arr[0][115] = '';
        //$this->reestrIn->setKeyField();

        return $arr;

    }

    /**
     *  Нулевой вариант данных при котором все данные валидны
     *  при текущих настойках моков
     */
    public function test_NullError()
    {
        $arr = $this->getArrayData();
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertEmpty($arrayError);
    }

    public function test_date()
    {
        $arr = $this->getArrayData();
        //$this->reestrOut->setDateCreateInvoice($this->getDataType($arr[0][99]));
        $arr[0][99] = "";
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[2], "Дата создания документа null не может быть пустым")
        );
    }

    public function test_validErrorFirstRow_testDoubleRPN()
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setMonth($arr[0][79]);
        $arr[0][79] = 7;
        //$this->reestrIn->setYear($arr[0][87]);
        $arr[0][87] = 2016;
        //$this->reestrIn->setNumBranch($arr[0][66]);
        $arr[0][66] = '578';
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        // var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[2], "Филиал уже подавал РПН за этот период ранее")
        );
    }

    public function test_validErrorFirstRow_testPravoBranch()
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setNumBranch($arr[0][66]);
        $arr[0][66] = '579';
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        // var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[2], "Филиал не имеет право подавать РПН на уровень ЦФ")
        );
    }

    public function dataFromValidDoc()
    {
        return [
            ['', "не может быть пустым"],
            ['hkhkh', " не верный номер документа"],
            ['7979jlj', " не верный номер документа"],
            ['797///k', " не верный номер документа"],
            ['797-_', " не верный номер документа"],
        ];
    }

    /**
     * @dataProvider dataFromValidDoc
     * @param $testData
     * @param $result
     */
    public function test_validNumDoc($testData, $result)
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setNumInvoice($arr[0][100]);
        $arr[0][100] = $testData;
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[2], $result)
        );
    }

    /**
     * @return array
     */
    public function dataFromValidTypeInvoiceFull()
    {
        return [
            ['', "не может быть пустым"],
            ['hkhkh', " не верный тип документа"],
            ['7979jlj', " не верный тип документа"],
            ['ПНП', " не верный тип документа"],
            ['ЧКЕ', " не верный тип документа"],
        ];
    }

    /**
     * @dataProvider dataFromValidTypeInvoiceFull
     */
    public function test_validTypeInvoiceFull($testData, $result)
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setTypeInvoiceFull($arr[0][134]);
        $arr[0][121] = $testData;
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[2], $result)
        );
    }

    public function dataFromValidTypeInvoice()
    {
        return [
            ['18', "Указан  не верный тип причины не выдачи документа покупателю"],
            ['hkhkh', "Указан  не верный тип причины не выдачи документа покупателю"],
            ['7979jlj', "Указан  не верный тип причины не выдачи документа покупателю"],
            ['ПНП', "Указан  не верный тип причины не выдачи документа покупателю"],
            ['ЧКЕ', "Указан  не верный тип причины не выдачи документа покупателю"],
        ];
    }

    /**
     * @dataProvider dataFromValidTypeInvoice
     */
    public function test_validTypeInvoice($testData, $result)
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setTypeInvoiceFull($arr[0][134]);
        $arr[0][119] = $testData;
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[2], $result)
        );
    }

    public function dataFromValidINN()
    {
        return [
            ['', "ИНН документа не может быть пустым"],
            ['12345678', "Длина ИНН не может быть меньше"],
            ['1234567890123', "Длина ИНН не может быть более "],
            ['ПНП', " должен содержать только цифры"],
            ['ЧКЕ012345678', " должен содержать только цифры"],
        ];
    }

    /**
     * @dataProvider dataFromValidINN
     * @param $testData
     * @param $result
     */
    public function test_validTypeINN($testData, $result)
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setInnClient($arr[0][104]);
        $arr[0][104] = $testData;
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[2], $result)
        );
    }


    public function dataFromValidZagSumm()
    {
        return [
            ['12345678-88', "содержит данные не того типа."],
            ['1234567890123в', "содержит данные не того типа."],
            ['ПНП', " содержит данные не того типа."],
            ['ЧКЕ012345678', "содержит данные не того типа."],
        ];
    }

    /**
     * @dataProvider dataFromValidZagSumm
     */
    public function test_validTypeZagSumm($testData, $result)
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setZagSumm($arr[0][106]);
        $arr[0][106] = $testData;
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[2], $result)
        );
    }

    public function dataFromValidZagSumm_float()
    {
        return [
            ['-7.2759576141834E-12'],
            ['2.8421709430404E-14'],
            ['1.8189894035459E-12'],
            ['0.010000000002037'],
            ['-2.3283064365387E-10'],
            ['9.0949470177293E-13'],
            ['0.0099999999997635'],
            ['0.010000000002037'],

        ];
    }

    /**
     * Тест случая передачи значения -7.2759576141834E-12
     * @dataProvider dataFromValidZagSumm_float
     */
    public function test_validTypeZagSumm_float($testdata)
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setZagSumm($arr[0][106]);
        $arr[0][106] = $testdata;
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertEmpty($arrayError);

    }


    public function dataFromValidBaza20()
    {
        return [
            ['12345678-88', "содержит данные не того типа."],
            ['1234567890123в', "содержит данные не того типа."],
            ['ПНП', " содержит данные не того типа."],
            ['ЧКЕ012345678', "содержит данные не того типа."],
        ];
    }

    /**
     * @dataProvider dataFromValidBaza20
     */
    public function test_validTypeBaza20($testData, $result)
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setZagSumm($arr[0][107]);
        $arr[0][107] = $testData;
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[2], $result)
        );
    }

    /**
     * Тест случая передачи значения -7.2759576141834E-12
     */
    public function test_validTypeBaza20_float()
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setZagSumm($arr[0][107]);
        $arr[0][107] = "-7.2759576141834E-12";
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertEmpty($arrayError);

    }
    public function dataFromValidRKE()
    {
        return [
            ['4546546'," ","", "Указан не верные реквизиты документа который корректировал РКЕ"],
            ['4546546',"4h","lhlhlk", "Указан не верные реквизиты документа который корректировал РКЕ"],
            ['',"4//4654","", "Указан не верные реквизиты документа который корректировал РКЕ"],
            ['',"4//4654","nhkhkhk", "Указан не верные реквизиты документа который корректировал РКЕ"],
            ['',"4//4654","", "Указан не верные реквизиты документа который корректировал РКЕ"],
        ];
    }

    /**
     * @dataProvider dataFromValidRKE
     * @param $testDataDateCreateInvoice
     * @param $testDataNumInvoice
     * @param $testDataPidstava
     * @param $result
     */
    public function test_validRKE($testDataDateCreateInvoice,$testDataNumInvoice,$testDataPidstava, $result)
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setTypeInvoiceFull($arr[0][121]);
        $arr[0][121] = "РКЕ";
        //$this->reestrIn->setRkeDateCreateInvoice($this->getDataType($arr[0][112]));
        $arr[0][112] = $testDataDateCreateInvoice;
        //$this->reestrIn->setRkeNumInvoice($arr[0][114]);
        $arr[0][114] = $testDataNumInvoice;
        //$this->reestrIn->setRkePidstava($arr[0][115]);
        $arr[0][115] = $testDataPidstava;
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[2], $result)
        );
    }
}
