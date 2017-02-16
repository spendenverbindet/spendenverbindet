<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProjectController extends Controller
{
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

    public function listBackendAction(){
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
                "currentDonators"=>$projects[$i]->getCurrentDonators(),
                "username"=>$projects[$i]->getUsers()->getUsername(),
                "category"=>$projects[$i]->getCategory()->getCategoryText()
            );
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }
    
    public function listFromCategoryAction($categoryId){
        $category = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($categoryId);

        $projects = $category->getProjects();


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

    public function showAction($projectName, $projectId){
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

    public function createAction ($title, $desciption, $shortinfo, $categoryId, $user, $targetAmount, $titlePictureUrl){

        $categoryId = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($categoryId);
        $user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find($user);

        $date = new \DateTime('now');

        if (!$categoryId || !$user) {
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
            $project->setCurrentAmount(0);
            $project->setCurrentDonators(0);
            $project->setCreatedAt($date);
            $project->setCategory($categoryId);
            $project->setUser($user);
            $project->setActive(true);
            
            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($project);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new \Symfony\Component\HttpFoundation\Response('Inserted Project successful');
        }
    }

    public function updateAction($projectId, $title, $desciption, $shortinfo, $categoryId, $user, $targetAmount, $currentAmount, $titlePictureUrl, $active){

        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

        if (!$project) {
            throw $this->createNotFoundException(
                'No category found for id '.$projectId
            );
        }
        
        $project->setTitle($title);
        $project->setTitlePictureUrl($titlePictureUrl);
        $project->setDescription($desciption);
        $project->setShortinfo($shortinfo);
        $project->setTargetAmount($targetAmount);
        $project->setCurrentAmount($currentAmount);
        $project->setCategory($categoryId);
        $project->setUser($user);
        $project->setActive($active);

        $em->flush();

        return new Response('Updated post successful');
    }

    public function deleteAction($projectId){

        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('HtlSpendenportalBundle:Category')->find($projectId);

        if (!$project) {
            throw $this->createNotFoundException(
                'No category found for id '.$project
            );
        }

        /* Schauen ob es für dieses Project childs existieren! */


        $em->remove($project);
        $em->flush();


        return new Response('Picture has been deleted!');
    }
}
