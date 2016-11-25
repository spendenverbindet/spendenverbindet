<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        // return $this->render('HtlSpendenportalBundle::layout.html.twig');

        return $this->render('HtlSpendenportalBundle::index.html.twig');

    }
}

