<?php

namespace Htl\SpendenportalBundle\Controller;

use Htl\SpendenportalBundle\Entity\Category;
use Htl\SpendenportalBundle\Entity\Donation;
use Htl\SpendenportalBundle\Entity\Follower;
use Htl\SpendenportalBundle\Entity\Picture;
use Htl\SpendenportalBundle\Entity\Post;
use Htl\SpendenportalBundle\Entity\Project;
use Htl\SpendenportalBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class FrontendController extends Controller
{
    
     /**
     * @Route("/listProject/{categoryId}")
     */
    public function listProjectsAction($categoryId){
        $category = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($categoryId);

        $projects = $category->getProjects();


        $responseArray = array();

        for($i=0;$i<count($projects);$i++){
            $item = array(
                "id"=>$projects[$i]->getId(), 
                "title"=>$projects[$i]->getTitle(), 
                "titlePictureUrl"=>$projects[$i]->getTitlePictureUrl(), 
                "description"=>$projects[$i]->getDescription(), 
                "shortinfo"=>$projects[$i]->getShortinfo(),
                "created_at"=>$projects[$i]->getCreatedAt(), 
                "targetAmount"=>$projects[$i]->getTargetAmount()
            );
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }
    
    
}