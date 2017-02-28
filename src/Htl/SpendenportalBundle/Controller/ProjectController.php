<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Htl\SpendenportalBundle\Entity\Project;

class ProjectController extends Controller
{
    public function listAction(){
            $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->findAll();

            $responseArray = array();

            $hasDonated = null;

            foreach ($projects as $project) {
                //if($project->getActive()) {
                    if ($this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {
                        $hasDonated = $this->hasDonated($project->getId());
                    }

                    $progress = ($project->getTargetAmount() == 0) ? 0 : floor(($project->getCurrentAmount() / $project->getTargetAmount()) * 100);

                    $item = array(
                        "id" => $project->getId(),
                        "title" => $project->getTitle(),
                        "titlePictureUrl" => $project->getTitlePictureUrl(),
                        "description" => $project->getDescription(),
                        "shortinfo" => $project->getShortinfo(),
                        "created_at" => $project->getCreatedAt()->format('d.m.Y'),
                        "targetAmount" => $project->getTargetAmount(),
                        "currentAmount" => $project->getCurrentAmount(),
                        "progress" => $progress,
                        "currentDonators" => $project->getCurrentDonators(),
                        "hasDonated" => $hasDonated
                    );
                    array_push($responseArray, $item);
                //}
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
    }

    public function listMyActiveAction(){
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $projects = $this->getUser()->getProjects();
            
            $responseArray = array();
            
            foreach ($projects as $project) {
                if ($project->getActive()) {
                    $progress = ($project->getTargetAmount() == 0) ? 0 : floor(($project->getCurrentAmount() / $project->getTargetAmount()) * 100);
                    $item = array(
                        "id" => $project->getId(),
                        "title" => $project->getTitle(),
                        "titlePictureUrl" => $project->getTitlePictureUrl(),
                        "description" => $project->getDescription(),
                        "shortinfo" => $project->getShortinfo(),
                        "created_at" => $project->getCreatedAt()->format('d.m.Y'),
                        "targetAmount" => $project->getTargetAmount(),
                        "currentAmount" => $project->getCurrentAmount(),
                        "progress" => $progress,
                        "currentDonators" => $project->getCurrentDonators(),
                    );
                    array_push($responseArray, $item);
                }
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
        }
        return new JsonResponse(null);
    }

    public function listMyFinishedAction(){
        //if ( $this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $projects = $this->getUser()->getProjects();

            $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(3)->getProjects();

            $responseArray = array();

            foreach ($projects as $project) {
                if (!$project->getActive()) {
                    $progress = ($project->getTargetAmount() == 0) ? 0 : floor(($project->getCurrentAmount() / $project->getTargetAmount()) * 100);
                    $item = array(
                        "id" => $project->getId(),
                        "title" => $project->getTitle(),
                        "titlePictureUrl" => $project->getTitlePictureUrl(),
                        "description" => $project->getDescription(),
                        "shortinfo" => $project->getShortinfo(),
                        "created_at" => $project->getCreatedAt()->format('d.m.Y'),
                        "targetAmount" => $project->getTargetAmount(),
                        "currentAmount" => $project->getCurrentAmount(),
                        "progress" => $progress,
                        "currentDonators" => $project->getCurrentDonators(),
                    );
                    array_push($responseArray, $item);
                }
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
        //}
        //return new JsonResponse(null);
    }

    public function listBackendAction(){
        //if ( $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->findAll();

            $responseArray = array();

            foreach ($projects as $project) {
                if ($project->getTargetAmount() == 0) {
                    $progress = 0;
                } else {
                    $progress = floor(($project->getCurrentAmount() / $project->getTargetAmount()) * 100);
                }
                $item = array(
                    "id" => $project->getId(),
                    "title" => $project->getTitle(),
                    "active" => $project->getActive(),
                    "titlePictureUrl" => $project->getTitlePictureUrl(),
                    "description" => $project->getDescription(),
                    "descriptionPrivate" => $project->getDescriptionPrivate(),
                    "shortinfo" => $project->getShortinfo(),
                    "created_at" => $project->getCreatedAt()->format('d.m.Y'),
                    "targetAmount" => $project->getTargetAmount(),
                    "currentAmount" => $project->getCurrentAmount(),
                    "progress" => $progress,
                    "currentDonators" => $project->getCurrentDonators(),
                    "username" => $project->getUsers()->getUsername(),
                    "category" => $project->getCategory()->getCategoryText()
                );
                array_push($responseArray, $item);
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
        //}
        //return new JsonResponse(null);
    }
    
    public function listFromCategoryAction($categoryId){
            $category = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($categoryId);

            $projects = $category->getProjects();


            $responseArray = array();

            for ($i = 0; $i < count($projects); $i++) {
                if ($projects[$i]->getTargetAmount() == 0) {
                    $progress = 0;
                } else {
                    $progress = floor(($projects[$i]->getCurrentAmount() / $projects[$i]->getTargetAmount()) * 100);
                }
                $item = array(
                    "id" => $projects[$i]->getId(),
                    "title" => $projects[$i]->getTitle(),
                    "titlePictureUrl" => $projects[$i]->getTitlePictureUrl(),
                    "description" => $projects[$i]->getDescription(),
                    "shortinfo" => $projects[$i]->getShortinfo(),
                    "created_at" => $projects[$i]->getCreatedAt()->format('d.m.Y'),
                    "targetAmount" => $projects[$i]->getTargetAmount(),
                    "currentAmount" => $projects[$i]->getCurrentAmount(),
                    "progress" => $progress,
                    "currentDonators" => $projects[$i]->getCurrentDonators()

                );
                array_push($responseArray, $item);
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
    }

    public function listFollowingAction(){
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {
            $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->findAll();

            $responseArray = array();


            foreach ($projects as $project) {

                if ($this->ifFollowing($project) == true) {
                    if($project->getTargetAmount()==0){
                        $progress = 0;
                    } else {
                        $progress = floor(($project->getCurrentAmount() / $project->getTargetAmount()) * 100);
                    }
                    $item = array(
                        "id" => $project->getId(),
                        "title" => $project->getTitle(),
                        "titlePictureUrl" => $project->getTitlePictureUrl(),
                        "shortinfo" => $project->getShortinfo(),
                        "created_at" => $project->getCreatedAt()->format('d.m.Y'),
                        "targetAmount" => $project->getTargetAmount(),
                        "currentAmount" => $project->getCurrentAmount(),
                        "progress" => $progress,
                        "currentDonators" => $project->getCurrentDonators(),
                        "hasDonated" => $this->hasDonated($project->getId()),
                    );
                    array_push($responseArray, $item);
                }
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
        }
        return new JsonResponse(null);
    }

    public function ifFollowing($projectId){
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {
            //anzahl der ProjectIds checken

            $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
            $follower = $repository->getFollowers();

            $user = $this->getUser();
            //$user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(1);

            foreach ($follower as $follower) {
                if ($follower->getUsers()->getId() == $user->getId()) {
                    return true;
                } else {
                    continue;
                }
            }
            return false;
        }
        return false;
    }

    public function showAction($projectId){
            $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

            $responseArray = array();
            if ($this->hasDonated($projectId)){
                $description = $projects->getDescriptionPrivate();
            } else {
                $description = $projects->getDescription();
            }
            $progress = floor(($projects->getCurrentAmount() / $projects->getTargetAmount()) * 100);

            $hasDonated = $this->hasDonated($projects->getId());

            $item = array(
                "id" => $projects->getId(),
                "title" => $projects->getTitle(),
                "active" => $projects->getActive(),
                "titlePictureUrl" => $projects->getTitlePictureUrl(),
                "description" => $description,
                "shortInfo" => $projects->getShortinfo(),
                "created_at" => $projects->getCreatedAt()->format('d.m.Y'),
                "created_at_backend" => $projects->getCreatedAt()->format('Y-m-d'),
                "targetAmount" => $projects->getTargetAmount(),
                "currentAmount" => $projects->getCurrentAmount(),
                "progress" => $progress,
                "currentDonators" => $projects->getCurrentDonators(),
                "category" => $projects->getCategory()->getCategoryText(),
                "hasDonated"=>$hasDonated

            );
            array_push($responseArray, $item);

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
    }

    public function hasDonated($projectId){
        //anzahl der ProjectIds checken

        if ( $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {

            $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
            $donations = $repository->getDonations();
    
            $user = $this->getUser();
            //$user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(1);
    
            foreach($donations as $donation){
                if($donation->getUsers()->getId() == $user->getId()){
                    return true;
                }
            }
    
            return false;

        }

        return null;

    }

    public function createAction ($title, $desciption, $desciptionPrivate, $shortinfo, $categoryId, $user, $targetAmount, $titlePictureUrl)
    {
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $categoryId = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($categoryId);
            $user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find($user);
    
            $date = new \DateTime('now');
    
            if (!$categoryId || !$user) {
                throw $this->createNotFoundException(
                    'No category found for id ' . $categoryId . ' or not User found for id ' . $user
                );
            } else {
    
                $project = new Project();
                $project->setTitle($title);
                $project->setTitlePictureUrl($titlePictureUrl);
                $project->setDescription($desciption);
                $project->setDescriptionPrivate($desciptionPrivate);
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
    }

    public function updateAction($projectId, $title, $desciption, $desciptionPrivate, $shortinfo, $categoryId, $user, $targetAmount, $currentAmount, $titlePictureUrl, $active){

        if ( $this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $em = $this->getDoctrine()->getManager();
            $project = $em->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

            if (!$project) {
                throw $this->createNotFoundException(
                    'No category found for id ' . $projectId
                );
            }

            $project->setTitle($title);
            $project->setTitlePictureUrl($titlePictureUrl);
            $project->setDescription($desciption);
            $project->setDescriptionPrivate($desciptionPrivate);
            $project->setShortinfo($shortinfo);
            $project->setTargetAmount($targetAmount);
            $project->setCurrentAmount($currentAmount);
            $project->setCategory($categoryId);
            $project->setUser($user);
            $project->setActive($active);

            $em->flush();

            return new JsonResponse('Updated post successful');
        }
        return new JsonResponse(false);
    }

    public function deleteAction($projectId)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER') && $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $em = $this->getDoctrine()->getManager();
            $project = $em->getRepository('HtlSpendenportalBundle:Category')->find($projectId);

            if (!$project) {
                throw $this->createNotFoundException(
                    'No category found for id ' . $project
                );
            }

            /* Schauen ob es für dieses Project childs existieren! */


            $em->remove($project);
            $em->flush();


            return new Response('Picture has been deleted!');
        }
        return new JsonResponse(false);
    }
}
