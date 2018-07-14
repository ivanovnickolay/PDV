<?php

namespace App\Controller;

use App\Entity\forForm\search\docFromParam;
use App\Form\search\SearchERPNType;
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

    public function searchERPN(Request $request)
    {
//        $response = new Response();
//        $response->setVary("X-Requested-With"); // <=========== Set the Vary header
        $search = new docFromParam();
        $form = $this->createForm(SearchERPNType::class,$search);
        // если запрос пришел не Ajax - "тупо" отдаем форму ввода
        if (!$request->isXmlHttpRequest()){
            return $this->render(
                'form/searchERPN.html.twig',
                array('form' => $form->createView())
            );
        } else{
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // ... сохранить собрание, переадресовать и т.д.
            }

        }



    }

}
