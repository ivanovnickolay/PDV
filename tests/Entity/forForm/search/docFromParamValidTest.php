<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 08.03.2019
 * Time: 19:36
 */

namespace Entity\forForm\search;

use App\Entity\forForm\search\docFromParam;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

/**
 * Тестирование процедуры валидации данных сущности
 *  - валидации ИНН
 *  - валидация дат
 *  - валидация номера документа
 * Class docFromParamValidTest
 * @package Entity\forForm\search
 */

class docFromParamValidTest extends TestCase
{
    /**
     * @var Validation
     */
    private $validator;

    public function setUp():void {
        $this->validator = Validation::createValidatorBuilder()
            ->addMethodMapping('loadValidatorMetadata')
            ->getValidator();
    }


    /**
     * Валидация сущности и возврат массива ошибок валидации
     * @param docFromParam $obj сущность для валидации
     * @return array массив ошибок валидации
     */
    private function getArrayError(docFromParam $obj){
        $errors = $this->validator->validate($obj);
        $arrayError= array();
        if (count($errors)!=0){
            foreach ($errors as $e){
                $arrayError[$e->getPropertyPath()]=$e->getMessage();
            }
        }
        return $arrayError;
    }

    /**
     * тестирование на правильную валидацию ИНН
     */
    public function testINNValidate()
    {
        $obj = new docFromParam();
//        $obj->setMonthCreate('3');
//        $obj->setYearCreate('2017');
            $obj->setINN("ghgdlgj");
            $arrayError = $this->getArrayError($obj);
            $this->assertEquals("ИНН ghgdlgj должен содержать только цифры ",$arrayError["INN"]);
                $obj->setINN("01212ghgdlgj");
                $arrayError = $this->getArrayError($obj);
                $this->assertEquals("ИНН 01212ghgdlgj должен содержать только цифры ",$arrayError["INN"]);
                    $obj->setINN("ghg1313dlgj");
                    $arrayError = $this->getArrayError($obj);
                    $this->assertEquals("ИНН ghg1313dlgj должен содержать только цифры ",$arrayError["INN"]);

        $obj->setINN("456464");
        $arrayError = $this->getArrayError($obj);
        $this->assertEquals("ИНН 456464 должен иметь длину или 10 или 12 символов !",$arrayError["INN"]);
            $obj->setINN("12345678901");
            $arrayError = $this->getArrayError($obj);
            $this->assertEquals("ИНН 12345678901 должен иметь длину или 10 или 12 символов !",$arrayError["INN"]);
                $obj->setINN("123456789012345");
                $arrayError = $this->getArrayError($obj);
                $this->assertEquals("ИНН 123456789012345 должен иметь длину или 10 или 12 символов !",$arrayError["INN"]);
                    $obj->setINN("1234567890");
                    $arrayError = $this->getArrayError($obj);
                    $this->assertEquals(0,count($arrayError));
                        $obj->setINN("123456789012");
                        $arrayError = $this->getArrayError($obj);
                        $this->assertEquals(0,count($arrayError));
   }

   public function dataFromNumDocError(){
        return[
            ["12/5","\"12/5\" - не верный номер документа "],
            ["12///5","\"12///5\" - не верный номер документа "],
            ["12//l5","\"12//l5\" - не верный номер документа "],
            ["555g","\"555g\" - не верный номер документа "],
            ["555/45","\"555/45\" - не верный номер документа "],
            ["545//","\"545//\" - не верный номер документа "],
            ["545///","\"545///\" - не верный номер документа "],
            ["545/","\"545/\" - не верный номер документа "],
            ["/545","\"/545\" - не верный номер документа "],
            ["545hh/","\"545hh/\" - не верный номер документа "],
        ];
   }
    /**
     * тестирование на выявление ошибок валидации
     * @dataProvider dataFromNumDocError
     */
    public function testNumDocValidateError($a,$b){
        $obj = new docFromParam();
        $obj->setNumDoc($a);
        $arrayError = $this->getArrayError($obj);
        $this->assertEquals($b,$arrayError["numDoc"]);
    }

    public function dataFromNumDocWithouError(){
        return [
            ["45//455"],
            ["455"],
        ];
    }
    /**
     * тестирование на выявление без ошибочной валидации
     * @dataProvider dataFromNumDocWithouError
     */
    public function testNumDocValidateWithouError($a){
        $obj = new docFromParam();
            $obj->setNumDoc($a);
            $arrayError = $this->getArrayError($obj);
            $this->assertEquals(0,count($arrayError));


    }

    /**
     * Тестирование контроля дат поиска документов и даты создания документа (при его заполнении)
     */
    public function testDataCreateDoc(){
        $obj = new docFromParam();
            $obj->setMonthCreate(3);
                $obj->setYearCreate(2017);

                    $obj->setDateCreateDoc(null);
                    $arrayError = $this->getArrayError($obj);
                    $this->assertEquals(0,count($arrayError));

                        $obj->setDateCreateDoc(new \DateTime("2017-03-15"));
                        $arrayError = $this->getArrayError($obj);
                        $this->assertEquals(0,count($arrayError));

                            $obj->setDateCreateDoc(new \DateTime("2017-04-15"));
                            $arrayError = $this->getArrayError($obj);
                            $this->assertEquals(1,count($arrayError));

                                $obj->setDateCreateDoc(new \DateTime("2019-04-15"));
                                $arrayError = $this->getArrayError($obj);
                                $this->assertEquals(1,count($arrayError));


    }
}
