<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('HtlSpendenportalBundle::index.html.twig');
    }

    public function entdeckenAction(){
        return $this->render('HtlSpendenportalBundle::entdecken.html.twig');
    }

    public function projektAction(){
        return $this->render('HtlSpendenportalBundle::projektdetail.html.twig');
    }

    public function abonniertAction(){
        return $this->render('HtlSpendenportalBundle::abonniert.html.twig');
    }

}

