<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 20.06.2018
 * Time: 11:02
 */

namespace App\Utilits\workToTypeVariable;

use PHPUnit\Framework\TestCase;

class workToTypeVariableFloatTest extends TestCase
{
    public function dataForTest(){
        return [
            ['-7.2759576141834E-12','0'],
            ['2.8421709430404E-14','0'],
            ['1.8189894035459E-12','0'],
            ['0.010000000002037','0.01'],
            ['-2.3283064365387E-10','0'],
            ['9.0949470177293E-13','0'],
            ['0.0099999999997635','0.01'],
            ['0.010000000002037','0.01'],
            ['12345678-88', '12345678-88'],
            ['1234567890123в', '1234567890123в'],
            ['ПНП', 'ПНП'],
            ['ЧКЕ012345678', 'ЧКЕ012345678'],
            ['0.58', '0.58'],
            ['-558.29', '-558.29'],
            ['558.29', '558.29'],
            ['012345678', '012345678'],

        ];
    }

    /**
     * @param $var
     * @param $res
     * @dataProvider dataForTest
     */
    public function testSetFloatVariable($var, $res)
    {
        $this->assertEquals(
            $res,
            workToTypeVariable::setFloatVariable($var)
        );

    }
}
