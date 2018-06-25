<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.01.2018
 * Time: 00:38
 */

namespace App\Utilits\loadDataExcel\createEntityForLoad\entityForLoad;

use App\Entity\ReestrbranchOut;
use App\Utilits\loadDataExcel\createReaderFile\getReaderExcel;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Validator\Validation;

/**
 * Тестирование на формирования объекта Реестра выданных НН. (Обязательства)
 * Тестирование проходит путем сравнивания прочитанных данных с эталоном
 * Файл с данными для теста testDataReestrOut_TAB2.xlsx
 * Class createReestrOutTest
 * @package App\Utilits\loadDataExcel\createEntityForLoad\entityForLoad
 */
class createReestrOutTest extends TestCase
{
    /**
     * Тестируем правильность формирования объекта с данными. Для этого:
     *  - читаем тестовый файл с данными
     *  - создаем объект нужного типа
     *  - сверяем полученные данные объекта с эталоном
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function testCreateReestr()
    {
        $fileName= __DIR__.'\\testDataReestrOut_TAB2.xlsx';
        //$reader= new getReaderExcel('C:\OSPanel\domains\PDV_UZ\tests\Utilits\loadDataExcel\createEntityForLoad\entityForLoad\testDataReestrOut_TAB2.xlsx');
        $reader= new getReaderExcel($fileName);
        $reader->createFilter('DR');
        $reader->getReader();
        $this->assertInstanceOf(BaseReader::class,$reader->getReader());
        $en=new createReestrOut();
        $reader->loadDataFromFileWithFilter(2);
        $arr=$reader->getRowDataArray(2);
        //var_dump($arr);
        $entity=$en->createReestr($arr);
        $this->assertInstanceOf(ReestrbranchOut::class,$entity);
        $this->assertEquals('7',$entity->getMonth());
        $this->assertEquals('2016',$entity->getYear());
        $this->assertEquals('678',$entity->getNumBranch());
        $this->assertEquals(
            new \DateTime('30.06.2016',new \DateTimeZone('Europe/Kiev')),
            $entity->getDateCreateInvoice()
        );
        $this->assertEquals('122//275',$entity->getNumInvoice());
        $this->assertEquals('ПНЕ',$entity->getTypeInvoiceFull());
        $this->assertEquals('ПАТ \'\'УКРАЄНСЬКА ЗАЛIЗНИЦЯ\'\', Регiональна фiлiя \'\'ПРИДНIПРОВСЬКА ЗАЛIЗНИЦЯ\'\'  СП \'\'ДНIПРОПЕТРОВСЬКЕ МОТОРВАГОННЕ ДЕПО\'\'',$entity->getNameClient());
        $this->assertEquals('400000000000',$entity->getInnClient());
        $this->assertEquals('3544.98',$entity->getZagSumm());
        $this->assertEquals('2954.15',$entity->getBaza20());
        $this->assertEquals('590.83',$entity->getPdv20());
        $this->assertEquals('0',$entity->getBaza7());
        $this->assertEquals('0',$entity->getPdv7());
        $this->assertEquals('0',$entity->getBaza0());
        $this->assertEquals('0',$entity->getBazaZvil());
        $this->assertEquals('0',$entity->getBazaNeObj());
        $this->assertEquals('0',$entity->getBazaZaMezhiTovar());
        $this->assertEquals('0',$entity->getBazaZaMezhiPoslug());
        $this->assertEquals(
            new \DateTime('01.01.2000',new \DateTimeZone('Europe/Kiev')),
            $entity->getRkeDateCreateInvoice()
        );
        $this->assertEquals(0,$entity->getRkeNumInvoice());
        $this->assertEquals(null,$entity->getRkePidstava());
        $this->assertEquals('122//275/ПНЕ/30-06-2016/400000000000',$entity->getKeyField());
    }

    /**
     * Тестируем правильность формирования объекта с данными. Для этого:
     *  - читаем тестовый файл с данными
     *  - создаем объект нужного типа
     *  - сверяем полученные данные объекта с эталоном
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function testCreateReestr2()
    {
        $fileName= __DIR__.'\\testDataReestrOut_TAB2.xlsx';
        //$reader= new getReaderExcel('C:\OSPanel\domains\PDV_UZ\tests\Utilits\loadDataExcel\createEntityForLoad\entityForLoad\testDataReestrOut_TAB2.xlsx');
        $reader= new getReaderExcel($fileName);
        $reader->createFilter('DR');
        $reader->getReader();
        $this->assertInstanceOf(BaseReader::class,$reader->getReader());
        $en=new createReestrOut();
        $reader->loadDataFromFileWithFilter(2);
        $arr=$reader->getRowDataArray(4);
        //var_dump($arr);
        $entity=$en->createReestr($arr);
        $this->assertInstanceOf(ReestrbranchOut::class,$entity);
        $this->assertEquals('7',$entity->getMonth());
        $this->assertEquals('2016',$entity->getYear());
        $this->assertEquals('678',$entity->getNumBranch());
        $this->assertEquals(
            new \DateTime('30.06.2016',new \DateTimeZone('Europe/Kiev')),
            $entity->getDateCreateInvoice()
        );
        $this->assertEquals('опо//275',$entity->getNumInvoice());
        $this->assertEquals('ПНП',$entity->getTypeInvoiceFull());
        $this->assertEquals('ПАТ \'\'УКРАЄНСЬКА ЗАЛIЗНИЦЯ\'\', Регiональна фiлiя \'\'ПРИДНIПРОВСЬКА ЗАЛIЗНИЦЯ\'\'  СП \'\'ДНIПРОПЕТРОВСЬКЕ МОТОРВАГОННЕ ДЕПО\'\'',$entity->getNameClient());
        $this->assertEquals('400000000000',$entity->getInnClient());
        $this->assertEquals('3544.98',$entity->getZagSumm());
        $this->assertEquals('2954.15',$entity->getBaza20());
        $this->assertEquals('590.83',$entity->getPdv20());
        $this->assertEquals('0',$entity->getBaza7());
        $this->assertEquals('0',$entity->getPdv7());
        $this->assertEquals('0',$entity->getBaza0());
        $this->assertEquals('0',$entity->getBazaZvil());
        $this->assertEquals('0',$entity->getBazaNeObj());
        $this->assertEquals('0',$entity->getBazaZaMezhiTovar());
        $this->assertEquals('0',$entity->getBazaZaMezhiPoslug());
        $this->assertEquals(
            new \DateTime('01.01.2000',new \DateTimeZone('Europe/Kiev')),
            $entity->getRkeDateCreateInvoice()
        );
        $this->assertEquals(0,$entity->getRkeNumInvoice());
        $this->assertEquals(null,$entity->getRkePidstava());
        $this->assertEquals('опо//275/ПНП/30-06-2016/400000000000',$entity->getKeyField());
    }

    /**
     * Тестирование Валидатора
     *  -   считать строку с файла
     *  -   создать объект на основании данных файла
     *  -   провести валидацию объекта
     *  -   сравнить количество ошибок выданных валидатором с ожидаемым
     * @link https://symfony.com/doc/current/components/validator/resources.html - метод вызова валидатора
     * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function testValidatorReestr(){
        $fileName= __DIR__.'\\testDataReestrOut_TAB2.xlsx';
        //$reader= new getReaderExcel('C:\OSPanel\domains\PDV_UZ\tests\Utilits\loadDataExcel\createEntityForLoad\entityForLoad\testDataReestrOut_TAB2.xlsx');
        $reader= new getReaderExcel($fileName);
        $reader->createFilter('DR');
        $reader->getReader();
        $this->assertInstanceOf(BaseReader::class,$reader->getReader());
        $en=new createReestrOut();
        $validator = Validation::createValidatorBuilder()
            ->addMethodMapping('loadValidatorMetadata')
            ->getValidator();
        $reader->loadDataFromFileWithFilter(2);
        // Тестовая строка без ошибок
        $arr=$reader->getRowDataArray(2);
        $entity=$en->createReestr($arr);
        $error = $validator->validate($entity);
        $this->assertEquals(0,count($error));
        //var_dump((string) $error);
            /*заложенные в файле ошибки
                1. Номер документа содержит буквы
                2. Тип причины 19
            */
            $arr=$reader->getRowDataArray(3);
            $entity=$en->createReestr($arr);
            $error = $validator->validate($entity);
            $this->assertEquals(2,count($error));
            //var_dump((string) $error);
                /*заложенные в файле ошибки
                    1. Номер документа содержит буквы
                    2. Тип причины 19
                    3. Тип документа ПНП
                */
                $arr=$reader->getRowDataArray(4);
                $entity=$en->createReestr($arr);
                $error = $validator->validate($entity);
                $this->assertEquals(3,count($error));
                //var_dump((string) $error);
                    /*заложенные в файле ошибки
                        1. Дата документа пустая
                        2. сумма содержит буквы
                        3. Тип документа РКЕ без указания документов которые уточняются
                    */
                    $arr=$reader->getRowDataArray(5);
                    $entity=$en->createReestr($arr);
                    $error = $validator->validate($entity);
                    $this->assertEquals(3,count($error));
                    //var_dump((string) $error);
                /*заложенные в файле ошибки
                    1. Дата документа пустая
                    2. сумма содержит буквы
                    3. Тип документа РКЕ без указания документов которые уточняются
                    4. Месяц РПН = 14
                    5. Год РПН 2021
                */
                $arr=$reader->getRowDataArray(6);
                $entity=$en->createReestr($arr);
                $error = $validator->validate($entity);
                $this->assertEquals(5,count($error));
                //var_dump((string) $error);
                /*заложенные в файле ошибки
                    1. Дата документа пустая
                    2. сумма содержит буквы
                    3. Тип документа РКЕ без указания документов которые уточняются
                    4. Месяц РПН = -14
                    5. Год РПН 2014
                */
                $arr=$reader->getRowDataArray(7);
                $entity=$en->createReestr($arr);
                $error = $validator->validate($entity);
                $this->assertEquals(5,count($error));
                //var_dump((string) $error);
            /*заложенные в файле ошибки
                1. Дата документа пустая
                2. сумма содержит буквы
                3. Тип документа РКЕ без указания документов которые уточняются
                4. Месяц РПН = -14
                5. Год РПН 2014
                6. Номер филиала пустой == сработают две проверки
                    -   содержать только цифры
                    -   для поля должна быть 3 символа
            */
            $arr=$reader->getRowDataArray(8);
            $entity=$en->createReestr($arr);
            $error = $validator->validate($entity);
            $this->assertEquals(7,count($error));
            //var_dump((string) $error);
        /*заложенные в файле ошибки
           1. сумма содержит буквы (шесть значений вместо цифр стоят буквы
         */
        $arr=$reader->getRowDataArray(9);
        $entity=$en->createReestr($arr);
        $error = $validator->validate($entity);
        $this->assertEquals(6,count($error));
        //var_dump((string) $error);
    }
}
