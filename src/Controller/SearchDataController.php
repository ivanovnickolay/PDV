<?php

namespace App\Controller;

use App\Entity\forForm\search\docFromParam;
use App\Form\search\SearchERPNType;
use App\Services\searchErpnFromParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SearchDataController контроллер операций связанных с поиском данных
 * @package App\Controller
 */
class SearchDataController extends Controller
{

    public function searchERPN(Request $request,searchErpnFromParam $searchErpnFromParam)
    {
        $search = new docFromParam();
        $form = $this->createForm(SearchERPNType::class,$search);
        // если запрос пришел не Ajax - "тупо" отдаем форму ввода
        if (!$request->isXmlHttpRequest()){
            return $this->render(
                'form/searchERPN.html.twig',
                array('form' => $form->createView())
            );
        } else{
            $param = $this->parseRequestsearchERPN($request);
            $searchErpnFromParam->setParamSearch($param);
            $arrayResult = $searchErpnFromParam->search();
            return $this->json($arrayResult);
        }
    }

    /**
     * Парсер запроса в объект параметров поиска
     * @param Request $request
     * @return docFromParam
     */
        private function parseRequestsearchERPN(Request $request):docFromParam{
            $obj = new docFromParam();
            $requestParam = $request->request->get('search_erpn');
            $obj->setMonthCreate($requestParam["monthCreate"]);
            $obj->setYearCreate($requestParam["yearCreate"]);
            $obj->setNumDoc($requestParam["numDoc"]);
            $obj->setDateCreateDoc($requestParam["dateCreateDoc"]);
            $obj->setTypeDoc($requestParam["typeDoc"]);
            $obj->setINN($requestParam["iNN"]);
            $obj->setRouteSearch($requestParam["routeSearch"]);
            return $obj;
        }
}
