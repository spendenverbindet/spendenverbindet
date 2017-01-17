<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class FrontendController extends Controller
{
    
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

    /**
     * @Route("/createProject")
     */
    public function createProject ($title, $desciption, $shortinfo, $categoryId, $user, $targetAmount, $titlePictureUrl){

        $category = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($categoryId);
        $user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find($user);

        if (!$category || !$user) {
            throw $this->createNotFoundException(
                'No category found for id '.$categoryId.' or not User found for id '.$user
            );
        }
        else{

            $project = new Project();
            $project->setTitle($title);
            $project->setTitlePictureUrl($titlePictureUrl);
            $project->setDescription($desciption);
            $project->setShortinfo($shortinfo);
            $project->setTargetAmount($targetAmount);
            $project->setCreatedAt(date('d.m.Y'));
            $project->setCategory($category);
            $project->setUser($user);
            $project->setActive(true);


            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($project);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new Response('Saved new product with id '.$project->getId());
        }
    }
}
