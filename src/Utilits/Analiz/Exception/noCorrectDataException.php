<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27.03.2017
 * Time: 10:44
 */

namespace App\Utilits\Analiz\Exception;


use Exception as ExceptionAlias;

/**
 * Вызывается при не корректной дате при вводе
 * месяц не в диапазоне 1 - 12
 * год не в заданном диапазоне
 * Class noCorrectData
 * @package AnalizPdvBundle\Model\Exception
 */
class noCorrectDataException extends ExceptionAlias
{

}