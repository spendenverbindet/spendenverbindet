<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProjectController extends Controller
{

    public function indexAction()
    {

        $projects = $this->getDoctrine()
            ->getRepository('HtlSpendenportalBundle:Project')->findAll();

        if (!$projects) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }

        return $this->render('BackendAdminBundle::listProjects.html.twig');
    }
    
    public function showaAction()
    {
        return $this->render('BackendAdminBundle::editProject.html.twig');
    }

    public function listAction(){
        $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->findAll();

        $responseArray = array();

        for($i=0;$i<count($projects);$i++){
            $progress = floor(($projects[$i]->getCurrentAmount()/$projects[$i]->getTargetAmount())*100);
            $item = array(
                "id"=>$projects[$i]->getId(),
                "title"=>$projects[$i]->getTitle(),
                "titlePictureUrl"=>$projects[$i]->getTitlePictureUrl(),
                "description"=>$projects[$i]->getDescription(),
                "shortinfo"=>$projects[$i]->getShortinfo(),
                "created_at"=>$projects[$i]->getCreatedAt()->format('d.m.Y'),
                "targetAmount"=>$projects[$i]->getTargetAmount(),
                "currentAmount"=>$projects[$i]->getCurrentAmount(),
                "progress"=>$progress,
                "currentDonators"=>$projects[$i]->getCurrentDonators()
            );
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }

    public function showAction($projectId){
        $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

        $responseArray = array();

        $progress = floor(($projects->getCurrentAmount()/$projects->getTargetAmount())*100);
        $item = array(
            "id"=>$projects->getId(),
            "title"=>$projects->getTitle(),
            "titlePictureUrl"=>$projects->getTitlePictureUrl(),
            "description"=>$projects->getDescription(),
            "shortinfo"=>$projects->getShortinfo(),
            "created_at"=>$projects->getCreatedAt()->format('d.m.Y'),
            "targetAmount"=>$projects->getTargetAmount(),
            "currentAmount"=>$projects->getCurrentAmount(),
            "progress"=>$progress,
        );
        array_push($responseArray, $item);

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }
/*
    public function showAction($productId)
    {
        $product = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->find($productId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$productId
            );
        }

        // ... do something, like pass the $product object into a template
    }*/
}
