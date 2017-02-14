<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BestenlisteController extends Controller
{
    public function listAction(){
        /*
        $bestenliste = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->findAll();

        $responseArray = array();

        for($i=0;$i<count($bestenliste);$i++){
            $item = array("id"=>$bestenliste[$i]->getId(),
                "pictureUrl"=>$bestenliste[$i]->getPictureUrl(),
                "created_at"=>$bestenliste[$i]->getCreatedAt());
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
        */
    }
}