<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FrontendController extends Controller
{
    /**
     * @Route("/categories")
     */
    public function listCategories(){

        $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category');
        $categories = $repository->findAll();

        $responseArray = array();

        for($i=0;$i<count($categories);$i++){
            $item = array("id"=>$categories[$i]->getId(), "name"=>$categories[$i]->getCategoryText());
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }

    /**
     * @Route("/listCategoryProdukte/{categoryId}")
     */
    public function listCategoryProjects($categoryId){

        $category = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($categoryId);
        $projects = $category->getProjects();

        /*
        print "<pre>";
        print_r ($products);
        print "</pre>";

        return "irgendwas";
        return new JsonResponse("");*/
        
    }
}
