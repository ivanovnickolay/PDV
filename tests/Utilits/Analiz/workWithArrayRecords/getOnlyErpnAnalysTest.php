<?php

namespace App\Utilits\Analiz\workWithArrayRecords;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class getOnlyErpnAnalysTest extends TestCase
{

    /**
     * Тестирование приватного метода buildPivotTable для построения базовой сводной таблицы
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function testBuildPivotTable()
    {
        $object = new getOnlyErpnAnalys(
            (new workWithArrayRecordsErpn(array())),
            (new workWithArrayRecordsReestr(array()))
        );

        $reflection = new ReflectionClass(getOnlyErpnAnalys::class);
        $method = $reflection->getMethod("buildPivotTable");
        $method->setAccessible(true);
        $parametersErpn = array (
            '03/2017/3819720424/РКЕ' => '-0.01',
            '03/2017/324773000000/РКЕ' => '0.00',
            '03/2017/345013000000/ПНЕ' => '23378.79',
            '03/2017/308979000000/ПНЕ' => '118743.00',
            '03/2017/1916705099/ПНЕ' => 16828.59,
            '03/2017/338065000000/ПНЕ' => 59092.65
        );
        $parametersReesrt = array (
            '03/2017/3819720424/РКЕ' => '-0.02',
            '03/2017/324773000000/РКЕ' => '0.10',
            '03/2017/345014000000/ПНЕ' => '23378.79',
            '03/2017/308978000000/ПНЕ' => '118743.00',
            '03/2017/1916705099/ПНЕ' => 16828.60,
            '03/2017/338065000000/ПНЕ' => 59092.65
        );

        $result =  $method->invoke($object, $parametersErpn,$parametersReesrt);
        // Проверим массив, который вернул метод buildPivotTable при построении сводной таблицы
        $this->assertEquals(count($result),2);
        $this->assertEquals(count($result[0]),5);
//        //$this->assertEquals($result[0]['key'],"03/2017/3819720424/РКЕ");
        $this->assertEquals($result[0]['month'],"03");
        $this->assertEquals($result[0]['year'],"2017");
        $this->assertEquals($result[0]['inn'],"345013000000");
        $this->assertEquals($result[0]['type'],"ПНЕ");
        $this->assertEquals($result[0]["PDV_Erpn"],"23378.79");

        $this->assertEquals($result[1]['month'],"03");
        $this->assertEquals($result[1]['year'],"2017");
        $this->assertEquals($result[1]['inn'],"308979000000");
        $this->assertEquals($result[1]['type'],"ПНЕ");
        $this->assertEquals($result[1]["PDV_Erpn"],"118743.00");

//        // вызываем метод buildPivotTable повторно что бы убедится - многократные вызовы его не обнуляют
        $result =  $method->invoke($object, $parametersErpn,$parametersReesrt);
        $this->assertEquals(count($result),3);

        // Проверим метод getArrayForExport - массив, который он возвращает должен быть равным массиву
        // возвращеннному методом buildPivotTable
        $this->assertEquals($result,$object->getArrayPivotTableForExport());

        // Проверим метод getUniqKeyForBuildListDocuments, который возвращает уникальные ключи сводной таблицы
        $this->assertEquals($object->getUniqKeyForBuildListDocuments(),
            array("03/2017/345013000000/ПНЕ",
                "03/2017/324773000000/РКЕ",
                "03/2017/308979000000/ПНЕ"));
        return null;
    }

}
