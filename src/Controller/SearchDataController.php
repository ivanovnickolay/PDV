<?php

namespace App\Controller;

use App\Entity\forForm\search\docFromParam;
use App\Form\search\SearchERPNType;

use App\Form\search\SearchReestrType;
use App\Services\searchDataFromParam\searchErpnFromParam;
use App\Services\searchDataFromParam\searchReestrFromParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SearchDataController контроллер операций связанных с поиском данных
 * @package App\Controller
 */
class SearchDataController extends Controller
{
    /**
     * Контроллер для поиска данных в ЕРПН
     * @param Request $request
     * @param searchErpnFromParam $searchErpnFromParam
     * @return JsonResponse|Response
     *
     */
    public function searchERPN(Request $request,searchErpnFromParam $searchErpnFromParam)
    {
          // если запрос пришел не Ajax - "тупо" отдаем форму ввода
        if (!$request->isXmlHttpRequest()){
            $search = new docFromParam();
            $form = $this->createForm(SearchERPNType::class,$search);
            return $this->render(
                'form/searchERPN.html.twig',
                array('form' => $form->createView())
            );
        } else{
            $param = $this->parseRequestSearch($request,"search_erpn");
            $searchErpnFromParam->setParamSearch($param);
            $arrayResult = $searchErpnFromParam->search();
            //return $this->json($arrayResult);
            if (key_exists('error',$arrayResult)){
                return new JsonResponse($arrayResult, 400);
            } else{
                return new JsonResponse($arrayResult, 200);
            };
            //return new JsonResponse($arrayResult, 200);

        }
    }

    /**
     * Парсер запроса в объект параметров поиска
     * @param Request $request
     * @param string $typeForm тип формы, данные которые "вытягиваются" из параметров $request
     * @return docFromParam
     */
        private function parseRequestSearch(Request $request, string $typeForm):docFromParam{
            $obj = new docFromParam();
           // $requestParam = $request->request->get('search_erpn');
            $requestParam = $request->request->get($typeForm);
            $obj->setMonthCreate($requestParam["monthCreate"]);
            $obj->setYearCreate($requestParam["yearCreate"]);
            $obj->setNumDoc($requestParam["numDoc"]);
            if (empty($requestParam["dateCreateDoc"])){
                $obj->setDateCreateDoc(null);
            }else{
                $obj->setDateCreateDoc(new \DateTime($requestParam["dateCreateDoc"]));
            }
            $obj->setTypeDoc($requestParam["typeDoc"]);
            $obj->setINN($requestParam["iNN"]);
            $obj->setRouteSearch($requestParam["routeSearch"]);
            return $obj;
        }

    /**
     * Контроллер поиска данных в Реестрах выданных и полученных налоговых накладных
     * @param Request $request
     * @param searchReestrFromParam $searchReestrFromParam
     * @return JsonResponse|Response
     */
        public function searchReestr(Request $request, searchReestrFromParam $searchReestrFromParam){
            $search = new docFromParam();
            $form = $this->createForm(SearchReestrType::class,$search);
            // если запрос пришел не Ajax - "тупо" отдаем форму ввода
            if (!$request->isXmlHttpRequest()){
                return $this->render(
                    'form/searchReestr.html.twig',
                    array('form' => $form->createView())
                );
            } else{
                $param = $this->parseRequestSearch($request,"search_reestr");
                $searchReestrFromParam->setParamSearch($param);
                $arrayResult = $searchReestrFromParam->search();
                //return $this->json($arrayResult);
                return new JsonResponse($arrayResult, 200);
            }
        }
}
