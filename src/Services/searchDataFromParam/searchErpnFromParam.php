<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15.07.2018
 * Time: 22:28
 */

namespace App\Services\searchDataFromParam;
use App\Entity\ErpnIn;
use App\Entity\ErpnOut;


/**
 * сервис поиска документов в ЕРПН по параметрам указанным в классе docFromParam
 * Class searchErpnFromParam
 * @package App\Services
 */
class searchErpnFromParam extends searchFromParamAbstract
{
//    /**
//     * @var EntityManager
//     */
//    private $em;
//    /**
//     * @var docFromParam
//     */
//    private $paramSearch;
//
//    public function __construct(EntityManagerInterface $entityManager){
//        $this->em = $entityManager;
//    }
//
//    public function setParamSearch(docFromParam $param){
//        $this->paramSearch = $param;
//    }
//
//    /**
//     * Валидация данных параметров
//     * если валидация успешна - возврат путой массив
//     * если валидация не успешна - возврат массив ошибок [поле с ошибкой]=>[описание ошибки валидации]
//     * @return array
//     */
//    private function validParamSearch():array {
//        $validator = Validation::createValidatorBuilder()
//            ->addMethodMapping('loadValidatorMetadata')
//            ->getValidator();
//        $errors = $validator->validate($this->paramSearch);
//        $arrayError= array();
//            if (count($errors)!=0){
//                foreach ($errors as $e){
//                    $arrayError[$e->getPropertyPath()]=$e->getMessage();
//                }
//            }
//            return $arrayError;
//    }
//    /*
//     * реализация поиска с выводом результатов
//     * $result["error"] = ошибки валидации
//     * $result["searchData"] = результаты поиска
//     * @return array
//     */
//    public function search():array {
//        $result = array();
//        $errorValidation = $this->validParamSearch();
//        if(count($errorValidation)!=0){
//            $result["error"] = $errorValidation;
//            return $result;
//        }
//        $searchData = $this->getArraySearchData();
//        if (count($searchData)!=0){
//            $result["searchData"]= $searchData;
//        }
//        return $result;
//    }

    /**
     * реализация поиска данных в таблицах и возврат результата как массива объектов
     * @return mixed
     */
    protected function getSearchData(){

     switch ($this->paramSearch->getRouteSearch()){
          case "Обязательства":
              $result =  $this->em->getRepository(ErpnOut::class)->searchDataFromParam($this->paramSearch);
              break;
          case "Кредит":
              $result =  $this->em->getRepository(ErpnIn::class)->searchDataFromParam($this->paramSearch);
              break;
      }
      return $result;
    }

    /**
     * получает результат запроса из базы
     * преобразовывает полученный масссив объктов в двухмерный массив простых данных
     * в зависимости от того, какая сущность обрабатывается вызываются анонимныые фукнции
     * для суммирования данных полей сущности
     *
     * @return array
     */
    protected function getArraySearchData():array {
            $searchData = $this->getSearchData();
            $arraySearchData = array();
            if (!is_null($searchData)){
                $counter = 0;

                /** @var ErpnIn|ErpnOut $sd */
                foreach ($searchData as $sd){
                    $arraySearchData[$counter]["NumInvoice"]=$sd->getNumInvoice();
                    $arraySearchData[$counter]["DateCreateInvoice"]=$sd->getDateCreateInvoice();
                    $arraySearchData[$counter]["TypeInvoiceFull"]=$sd->getTypeInvoiceFull();
                    $arraySearchData[$counter]["InnClient"]=$sd->getInnClient();
                    $arraySearchData[$counter]["NameClient"]=$sd->getNameClient();
                    $arraySearchData[$counter]["SumaInvoice"]=$sd->getSumaInvoice();
                    $arraySearchData[$counter]["BazaInvoice"]=$sd->getBazaInvoice();
                    $arraySearchData[$counter]["Pdvinvoice"]=$sd->getPdvinvoice();
                    $arraySearchData[$counter]["NameVendor"]=$sd->getNameVendor();
                    $arraySearchData[$counter]["NumBranchVendor"]=$sd->getNumBranchVendor();
                    $counter++;
                }
            }
            return $arraySearchData;
        }
}

