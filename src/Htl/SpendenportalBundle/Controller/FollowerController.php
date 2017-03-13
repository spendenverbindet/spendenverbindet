<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Htl\SpendenportalBundle\Entity\Follower;

class FollowerController extends Controller
{
    /*
    public function testCall(Request $request)
    {

        if ( $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') || $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED') ) {

            $user = $this->getUser();
            return new JsonResponse($user->getUserName());

        }

        return new JsonResponse('error youre not logged in');
    }
    */

    public function ifFollowingAction($projectId){
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {
        //anzahl der ProjectIds checken

        $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
        $follower = $repository->getFollowers();

            $user = $this->getUser();
            //$user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(1);


            foreach($follower as $follower){
                if($follower->getUsers()->getId() == $user->getId()){
                    return new JsonResponse(true);
                } else {
                    continue;
                }
            }

            return new JsonResponse(false);

        }

        return new JsonResponse(false);

    }

    public function listFromProjectAction($projectId){
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
            $follower = $repository->getFollowers();

            $responseArray = array();

            for ($i = 0; $i < count($follower); $i++) {
                $item = array(
                    "id" => $follower[$i]->getId(),
                    "user" => $follower[$i]->getUsers()->getUsername(),
                    "userId" => $follower[$i]->getUsers()->getId(),
                    "projects" => $follower[$i]->getProjects()->getTitle(),
                    "followedSince" => $follower[$i]->getFollowedSince()->format('d.m.Y')
                );
                array_push($responseArray, $item);
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
        }
        return new JsonResponse('not logged in');
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
    
    public function createAction ($projectId) {

        if ( $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {

            $projectId = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
            $user = $this->getUser();
            $date = new \DateTime('now');

            if (false) {
                throw $this->createNotFoundException(
                    'No category found for id  or not User found for id '
                );
            } else {
                //*
                $follower = new Follower();
                $follower->setFollowedSince($date);
                $follower->setProjects($projectId);
                $follower->setUsers($user);

                $em = $this->getDoctrine()->getManager();

                // tells Doctrine you want to (eventually) save the Product (no queries yet)
                $em->persist($follower);

                // actually executes the queries (i.e. the INSERT query)
                $em->flush();

                return new JsonResponse($user->getUsername().' has followed '.$follower->getProjects()->getTitle());
            }

        }

        return new JsonResponse(null);
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

    public function deleteAction($projectId){

        if ( $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Follower');

        $followers = $repository->findAll();

        $user = $this->getUser();


            foreach ($followers as $follower){

                if($follower->getUsers()->getId() == $user->getId() && $follower->getProjects()->getId() == $projectId){

                    $em->remove($follower);
                    $em->flush();

                    return new JsonResponse($user->getUsername().' has unfollowed '.$follower->getProjects()->getTitle());
                }
            }

    }

    }
}
