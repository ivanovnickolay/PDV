<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 29.07.2016
 * Time: 23:59
 */

namespace App\Utilits\loadDataExcel\ValidEntity;


use Doctrine\ORM\EntityManager;
use App\Entity\SprBranch;

/**
 * Class validBranch
 * @package AnalizPdvBundle\Utilits\ValidEntity
 */
class validBranch
{
 private $em;
public function __construct(EntityManager $em)
{
    $this->em=$em;
}
        /**
     * Проверка номера филиала
     * Условия проверки:
     * количество символов = 3 и
     * только цифры
     * @param $NumBranch string номер филиала
     * @return bool
         * true - проверка пройда успешно
     */
    public function validNumBranch($NumBranch)
 {
     $resultValid=false;
     if ((strlen($NumBranch)==3) and preg_match("/[0-9]{3}/",$NumBranch))
     {
         $resultValid=true;
     }
     return $resultValid;
 }


    /**
     * Проверка уникальности номера фидиала в базе
     * @param $NumBranch string  номер филиала
     * @return bool
     */
    public function validUniqBranch($NumBranch)
    {
        $Branch=$this->em->getRepository(SprBranch::class)->findBy(array('NumBranch' => $NumBranch));
        if(count($Branch)==0)
            {
                return true;
            } else
            {
                return false;
            }


    }

    /**
     * Организация проверки всех условий при помощи одного вызова функции
     * @param SprBranch $data
     * @return bool
     *
     */
    public function isValid(SprBranch $data)
    {

        $validData=false;
        if ($this->validNumBranch($data->getNumBranch()))
        {
            $validData=true;

        } else
            {
                $validData=false;
            }
        if(($this->validUniqBranch($data->getNumBranch())) and $validData )
        {
            $validData=true;
        }
        else
        {
            $validData=false;
        }
        return $validData;
    }
}