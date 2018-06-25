<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 20.06.2018
 * Time: 10:57
 */

namespace App\Utilits\workToTypeVariable;

/**
 * класс реализует функциональность преобразования и проверки типов значений
 * перед их использованием
 * Class workToTypeVariable
 * @package App\Utilits\workToTypeVariable
 */
class workToTypeVariable
{
    /**
     * Проверка значений типа float перед присваивания его полю сущности
     * -    если передано пустое значение = вернем ноль
     * -    если передано не цифровое значениие = вернем это значение (при валидации отследим)
     * -    если передано цифровое значение
     *      -   оно меньше 0,01 - то вернем ноль
     *      -   оно больше 0,01 - вернем значение
     * @param $var
     * @return float|int
     */
    public static function setFloatVariable($var){
        if (empty($var)){
            return 0;
        }
        if (is_numeric($var)){
            return round($var,2);
        } else{
            return $var;
        }
}

}