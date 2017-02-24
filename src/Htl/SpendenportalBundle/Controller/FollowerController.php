<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class FollowerController extends Controller
{
    public function testCall(Request $request)
    {

        if ( $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') || $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED') ) {

            $user = $this->getUser();
            return new JsonResponse($user->getUserName());

        }

        return new JsonResponse('error youre not logged in');
    }

    public function ifFollowingAction($projectId){

        $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
        $follower = $repository->getFollowers();

        
        if ( $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') || $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED') ) {

            $user = $this->getUser();

        }

        foreach($follower as $follower){
            if($follower->getUsers()->getId() == $user->getId()){
                return new JsonResponse(true);
            } else {
                return new JsonResponse(false);
            }
        }

    }

    public function listFromProjectAction($projectId){

        $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
        $follower = $repository->getFollowers();

        $responseArray = array();

        for($i=0;$i<count($follower);$i++){
            $item = array(
                "id"=>$follower[$i]->getId(),
                "user"=>$follower[$i]->getUsers()->getUsername(),
                "userId"=>$follower[$i]->getUsers()->getId(),
                "projects"=>$follower[$i]->getProjects()->getTitle(),
                "followedSince"=>$follower[$i]->getFollowedSince()->format('d.m.Y')
            );
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }
    
    public function listAction(){

        $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Follower');
        $follower = $repository->findAll();

        $responseArray = array();

        for($i=0;$i<count($follower);$i++){
            $item = array(
                "id"=>$follower[$i]->getId(),
                "users"=>$follower[$i]->getUsers(),
                "projects"=>$follower[$i]->getProjects(),
                "followedSince"=>$follower[$i]->getFollowedSince());
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }
    
    public function createAction ($projectId, $userId) {


        $projectId = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
        $userId = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find($userId);

        $date = new \DateTime('now');

        if (false) {
            throw $this->createNotFoundException(
                'No category found for id  or not User found for id '
            );
        }
        else{
            //*
            $follower = new Follower();
            $follower->setFollowedSince($date);
            $follower->setProjects($projectId);
            $follower->setUsers($userId);

            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($follower);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new \Symfony\Component\HttpFoundation\Response('Inserted Project with id ').$follower->getId();
        }
    }

    /*
    public function updateAction($followerId,$newProject,$newUser){

        $em = $this->getDoctrine()->getManager();
        $follower = $em->getRepository('HtlSpendenportalBundle:Category')->find($followerId);

        if (!$follower) {
            throw $this->createNotFoundException(
                'No category found for id '.$followerId
            );
        }

        /*$follower->setFollowedSince($date);
        $follower->setProjects($newProject);
        $follower->setUsers($newUser);

        $em->flush();

        return new Response('Updated category successful');
    }
    */

    public function deleteAction($followerId){

        $em = $this->getDoctrine()->getManager();
        $follower = $em->getRepository('HtlSpendenportalBundle:Category')->find($followerId);

        if (!$follower) {
            throw $this->createNotFoundException(
                'No category found for id '.$followerId
            );
        }

        /* Schauen ob es für diese Category childs existieren! */


        $em->remove($follower);
        $em->flush();


        return new Response('Category has been deleted!');
    }
}
