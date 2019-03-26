<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21.03.2019
 * Time: 00:43
 */

namespace App\Services\searchDataFromParam;


use App\Entity\forForm\search\docFromParam;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validation;

abstract class searchFromParamAbstract
{

    protected $em;
    /**
     * @var docFromParam
     */
    protected $paramSearch;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    public function setParamSearch(docFromParam $param){
        $this->paramSearch = $param;
    }

    /**
     * Валидация данных параметров
     * если валидация успешна - возврат путой массив
     * если валидация не успешна - возврат массив ошибок [поле с ошибкой]=>[описание ошибки валидации]
     * @return array
     */
    private function validParamSearch():array {
        $validator = Validation::createValidatorBuilder()
            ->addMethodMapping('loadValidatorMetadata')
            ->getValidator();
        $errors = $validator->validate($this->paramSearch);
        $arrayError= array();
        if (count($errors)!=0){
            foreach ($errors as $e){
                $arrayError[$e->getPropertyPath()]=$e->getMessage();
            }
        }
        return $arrayError;
    }
    /*
     * реализация поиска с выводом результатов
     * $result["error"] = ошибки валидации
     * $result["searchData"] = результаты поиска
     * @return array
     */
    public function search():array {
        $result = array();
        $errorValidation = $this->validParamSearch();
        if(count($errorValidation)!=0){
            $result["error"] = $errorValidation;
            return $result;
        }
        $searchData = $this->getArraySearchData();
        if (count($searchData)!=0){
            $result["searchData"]= $searchData;
        }
        return $result;
    }

    /**
     * реализация поиска данных в таблицах и возврат результата как массива объектов
     * @return mixed
     */
     abstract protected function getArraySearchData():array;

    /**
     * получает результат запроса из базы
     * преобразовывает полученный масссив объктов в двухмерный массив простых данных
     * @uses getArraySearchData для получения данных из базы анных
     * @return array
     */
     abstract protected function getSearchData();
}