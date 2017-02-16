<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PostController extends Controller
{
    public function listFromProjectAction($projectId){
        $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

        $post = $projects->getPosts();


        $responseArray = array();

        for($i=0;$i<count($post);$i++){
            $item = array(
                "id"=>$projects[$i]->getId(),
                "postPictureUrl"=>$projects[$i]->getPostPictureUrl(),
                "postText"=>$projects[$i]->getPosttText(),
                "created_at"=>$projects[$i]->getCreatedAt()
            );
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }
    
    public function createPostAction ($projectId, $postPictureUrl, $postText) {

        $projectId = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

        $date = new \DateTime('now');

        if (false) {
            throw $this->createNotFoundException(
                'No category found for id  or not User found for id '
            );
        }
        else{
            //$data = $request->request->all(); $data["url"]
            $post = new Post();
            $post->setPostPictureUrl($postPictureUrl);
            $post->setPostText($postText);
            $post->setCreatedAt($date);
            $post->setProjects($projectId);


            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($post);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new \Symfony\Component\HttpFoundation\Response('Inserted Post successful');
        }
    }

    public function updateAction($postId,$newUrl,$newPostText){

        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('HtlSpendenportalBundle:Post')->find($postId);

        if (!$post) {
            throw $this->createNotFoundException(
                'No category found for id '.$postId
            );
        }

        $post->setPostPictureUrl($newUrl);
        $post->setPostText($newPostText);

        $em->flush();

        return new Response('Updated post successful');
    }
    
    public function deleteAction($postId){

        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('HtlSpendenportalBundle:Category')->find($postId);

        if (!$post) {
            throw $this->createNotFoundException(
                'No category found for id '.$postId
            );
        }

        /* Schauen ob es für diesen Post childs existieren! */


        $em->remove($post);
        $em->flush();


        return new Response('Picture has been deleted!');
    }
}