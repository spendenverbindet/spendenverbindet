<?php

namespace Htl\SpendenportalBundle\Controller;

use Htl\SpendenportalBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class FrontendController extends Controller
{
    
    public function listCategoriesAction(){

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
     * @Route("/listProdukte/{categoryId}")
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
                "shortinfo"=>$projects[$i]->getShortinfo, 
                "created_at"=>$projects[$i]->getCreatedAt(), 
                "targetAmount"=>$projects[$i]->getTargetAmount()
            );
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);

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
    public function createProjectAction (Request $request){
        //$title, $desciption, $shortinfo, $categoryId, $user, $targetAmount, $titlePictureUrl

        /*
        $category = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($categoryId);
        $user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find($user);
        */
        $categoryId = 1;
        $user = 1;

        $date = new \DateTime('now');

        if (!$categoryId || !$user) {
            throw $this->createNotFoundException(
                'No category found for id '.$categoryId.' or not User found for id '.$user
            );
        }
        else{
            $data = $request->request->all();

            $project = new Project();
            $project->setTitle($data["title"]);
            $project->setTitlePictureUrl($data["titlePictureUrl"]);
            $project->setDescription($data["description"]);
            $project->setShortinfo($data["shortinfo"]);
            $project->setTargetAmount($data["targetAmount"]);
            $project->setCreatedAt($date);
            $project->setCategory($data["category"]);
            $project->setFollowers($data["category"]);
            $project->setUsers($data["user"]);
            $project->setActive(true);



            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($project);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new \Symfony\Component\HttpFoundation\Response('Saved new product with id ');//.$project->getId()
        }
    }
}
/*
 * $project = new Project();
            $project->setTitle($title);
            $project->setTitlePictureUrl($titlePictureUrl);
            $project->setDescription($desciption);
            $project->setShortinfo($shortinfo);
            $project->setTargetAmount($targetAmount);
            $project->setCreatedAt(date('d.m.Y'));
            $project->setCategory($categoryId);
            $project->setUser($user);
            $project->setActive(true);
 */