<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function index()
    {
        // replace this line with your own code!
        return $this->render('base.html.twig');
    }
}

/*
 * To install them, you can run: npm install --save core-js/modules/es.array.for-each core-js/modules/es.array.index-of core-js/modules/es.date.to-string core-js/modules/es.object.define-property core-js/modules/es.object.to-string core-js/modules/es.regexp.exec core-js/modules/es.regexp.to-string core-js/modules/es.string.match core-js/modules/es.string.replace core-js/modules/es.string.split core-js/modules/web.dom-collections.for-each

 *
 */