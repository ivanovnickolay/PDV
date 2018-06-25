<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.12.2016
 * Time: 22:12
 */

namespace App\Entity\forForm\validationConstraint;


use Symfony\Component\Validator\Constraint;

/**
 * Валидатор проверяет правильность написания номера документа
 *
 * @link http://symfony.com/doc/current/validation/custom_constraint.html#class-constraint-validator
 * @link https://knpuniversity.com/screencast/question-answer-day/custom-validation-property-path#creating-a-proper-custom-validation-constraint
 * Class ContainsNumDoc
 * @package AnalizPdvBundle\Entity\forForm\validationConstraint
 */
class ContainsNumDoc extends Constraint
{
	public $message = '"%string%" - не верный номер документа ';



}