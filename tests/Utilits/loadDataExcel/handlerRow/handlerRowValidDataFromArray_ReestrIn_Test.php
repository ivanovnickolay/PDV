<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 17.02.2018
 * Time: 21:58
 */

namespace App\Tests\Utilits\handlerRow;

use App\Entity\ReestrbranchIn;
use App\Entity\Repository\ReestrBranch_in;
use App\Entity\SprBranch;
use App\Utilits\loadDataExcel\configLoader\configLoadReestrIn;
use App\Utilits\loadDataExcel\handlerRow\handlerRowsValid;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;


/**
        * Тестирование функциональности класса handlerRowsValid при проверке РПН кредита.
        * При помощи моков делаем подмену методов и проводим тестирование
        * путем считываения специально подготовленный массив с данными для формирования объекта сущности
        * Таким образом исключается дополнительное тестирование классов
        *  - getReaderExcel
        *  - configLoadReestrIn
        *
 */

class handlerRowValidDataFromArray_ReestrIn_Test extends TestCase
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
            $repoReestrIn = $this->createMock(ReestrBranch_in::class);
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
                array(ReestrbranchIn::class, $repoReestrIn)
            );
            $objectManager = $this->createMock(EntityManager::class);
            $objectManager->expects($this->any())
                ->method('getRepository')
                ->will($this->returnValueMap($mapReestr));

            $configLoader = new configLoadReestrIn();
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
        //$this->reestrIn->setDateGetInvoice(//$this->getDataType($arr[0][105]));
        //$arr[0][105]=new \DateTime("2017-01-01 00:00:00",new \DateTimeZone('Europe/Kiev'));
        $arr[0][105] = "42564";
        //$this->reestrIn->setDateCreateInvoice(//$this->getDataType($arr[0][126]));
        $arr[0][126] = "42552";
        //$this->reestrIn->setNumInvoice($arr[0][106]);
        $arr[0][106] = '15';
        //$this->reestrIn->setTypeInvoiceFull($arr[0][134]);
        $arr[0][134] = "ПНЕ";
        //$this->reestrIn->setNameClient($arr[0][108]);
        $arr[0][108] = "Рога и копыта";
        //$this->reestrIn->setInnClient($arr[0][109]);
        $arr[0][109] = "100000000000";
        //$this->reestrIn->setZagSumm($arr[0][111]);
        $arr[0][111] = '12124.22';
        //$this->reestrIn->setBaza20($arr[0][113]);
        $arr[0][113] = "1212.11";
        //$this->reestrIn->setPdv20($arr[0][116]);
        $arr[0][116] = "1213.22";
        //$this->reestrIn->setBaza7($arr[0][114]);
        $arr[0][114] = 0;
        //$this->reestrIn->setPdv7($arr[0][117]);
        $arr[0][117] = 0;
        //$this->reestrIn->setBaza0($arr[0][115]);
        $arr[0][115] = 0;
        //$this->reestrIn->setPdv0($arr[0][118]);
        $arr[0][118] = 0;
        //$this->reestrIn->setBazaZvil($arr[0][120]);
        $arr[0][120] = 0;
        //$this->reestrIn->setPdvZvil($arr[0][95]);
        $arr[0][95] = 0;
        //$this->reestrIn->setBazaNeGos($arr[0][98]);
        $arr[0][98] = 0;
        //$this->reestrIn->setPdvNeGos($arr[0][101]);
        $arr[0][101] = 0;
        //$this->reestrIn->setBazaZaMezhi($arr[0][103]);
        $arr[0][103] = 0;
        //$this->reestrIn->setPdvZaMezhi($arr[0][104]);
        $arr[0][104] = 0;
        //$this->reestrIn->setRkeDateCreateInvoice($this->getDataType($arr[0][122]));
        $arr[0][122] = '';
        //$this->reestrIn->setRkeNumInvoice($arr[0][123]);
        $arr[0][123] = '';
        //$this->reestrIn->setRkePidstava($arr[0][124]);
        $arr[0][124] = '';
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
        //$this->reestrIn->setDateGetInvoice(//$this->getDataType($arr[0][105]));
        $arr[0][105] = "";
        //$this->reestrIn->setDateCreateInvoice(//$this->getDataType($arr[0][126]));
        $arr[0][126] = "";
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[2], "Дата создания документа null не может быть пустым")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[2], "Дата получения документа null не может быть пустым")
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
     */
    public function test_validNumDoc($testData, $result)
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setNumInvoice($arr[0][106]);
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
        $arr[0][134] = $testData;
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
     */
    public function test_validTypeINN($testData, $result)
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setInnClient($arr[0][109]);
        $arr[0][109] = $testData;
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
        //$this->reestrIn->setZagSumm($arr[0][111]);
        $arr[0][111] = $testData;
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
    public function test_validTypeZagSumm_float()
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setZagSumm($arr[0][111]);
        $arr[0][111] = "-7.2759576141834E-12";
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
        //$this->reestrIn->setZagSumm($arr[0][111]);
        $arr[0][111] = $testData;
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
        //$this->reestrIn->setZagSumm($arr[0][111]);
        $arr[0][111] = "-7.2759576141834E-12";
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
     */
    public function test_validRKE($testDataDateCreateInvoice,$testDataNumInvoice,$testDataPidstava, $result)
    {
        $arr = $this->getArrayData();
        //$this->reestrIn->setTypeInvoiceFull($arr[0][134]);
        $arr[0][134] = "РКЕ";
        //$this->reestrIn->setRkeDateCreateInvoice($this->getDataType($arr[0][122]));
        $arr[0][122] = $testDataDateCreateInvoice;
        //$this->reestrIn->setRkeNumInvoice($arr[0][123]);
        $arr[0][123] = $testDataNumInvoice;
        //$this->reestrIn->setRkePidstava($arr[0][124]);
        $arr[0][124] = $testDataPidstava;
        $this->handlerRow->handlerRow($arr);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[2], $result)
        );
    }

    /**
     * Тестирование неизменности месяца, года и номера филила
     */
    public function test_verifyStabilityIndicators_validMonth()
    {
        $arr1 = $this->getArrayData();
        $arr2 = $this->getArrayData();
        $arr2[0][79]=9;
        $this->handlerRow->handlerRow($arr1);
        $this->handlerRow->handlerRow($arr2);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[3], "Месяц реестра не соответствует месяцу указанному в первой строке файла!")
        );

    }

    /**
     * Тестирование неизменности месяца, года и номера филила
     */
    public function test_verifyStabilityIndicators_validBranch()
    {
        $arr1 = $this->getArrayData();
        $arr2 = $this->getArrayData();
        $arr2[0][66]="700";
        $this->handlerRow->handlerRow($arr1);
        $this->handlerRow->handlerRow($arr2);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[3], "Номер филиала реестра не соответствует номеру указанному в первой строке файла!")
        );

    }
    /**
     * Тестирование неизменности месяца, года и номера филила
     */
    public function test_verifyStabilityIndicators_validYear()
    {
        $arr1 = $this->getArrayData();
        $arr2 = $this->getArrayData();
        $arr2[0][87]=2017;
        $this->handlerRow->handlerRow($arr1);
        $this->handlerRow->handlerRow($arr2);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
       // var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[3], "Год реестра не соответствует году указанному в первой строке файла!")
        );
    }

    /**
     * Тестирование неизменности месяца, года и номера филила
     */
    public function test_verifyStabilityIndicators_validMonthYear()
    {
        $arr1 = $this->getArrayData();
        $arr2 = $this->getArrayData();
        $arr2[0][79]=9;
        $arr2[0][87]=2017;
        $this->handlerRow->handlerRow($arr1);
        $this->handlerRow->handlerRow($arr2);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[3], "Месяц реестра не соответствует месяцу указанному в первой строке файла!")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[3], "Год реестра не соответствует году указанному в первой строке файла!")
        );
    }
    /**
     * Тестирование неизменности месяца, года и номера филила
     */
    public function test_verifyStabilityIndicators_validMonthYearBranch()
    {
        $arr1 = $this->getArrayData();
        $arr2 = $this->getArrayData();
        $arr2[0][79]=9;
        $arr2[0][87]=2017;
        $arr2[0][66]="700";
        $this->handlerRow->handlerRow($arr1);
        $this->handlerRow->handlerRow($arr2);
        $arrayError = $this->handlerRow->getResultHandlingAllRows();
        //var_dump($arrayError);
        $this->assertNotEmpty($arrayError);
        $this->assertEquals(
            1,
            substr_count($arrayError[3], "Месяц реестра не соответствует месяцу указанному в первой строке файла!")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[3], "Год реестра не соответствует году указанному в первой строке файла!")
        );
        $this->assertEquals(
            1,
            substr_count($arrayError[3], "Номер филиала реестра не соответствует номеру указанному в первой строке файла!")
        );
    }
}
