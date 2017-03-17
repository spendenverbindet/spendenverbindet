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

    public function empfaenger_dashboardAction(){
        return $this->render('HtlSpendenportalBundle::empfaenger_dashboard.html.twig');
    }

    public function projekt_erstellenAction(){
        return $this->render('HtlSpendenportalBundle::projekt_erstellen.html.twig');
    }

    public function projekt_bearbeitenAction(){
        return $this->render('HtlSpendenportalBundle::projekt_bearbeiten.html.twig');
    }

    public function mein_profil_empfaengerAction(){
        return $this->render('HtlSpendenportalBundle::mein_profil_empfaenger.html.twig');
    }

    public function mein_profil_spenderAction(){
        return $this->render('HtlSpendenportalBundle::mein_profil_spender.html.twig');
    }

}

