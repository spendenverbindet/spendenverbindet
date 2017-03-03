<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Htl\SpendenportalBundle\Entity\Post;

class PostController extends Controller
{
    public function listFromProjectAction($projectId){
        if ($this->get('security.authorization_checker')->isGranted('ROLE_DONATOR') && $this->hasDonated($projectId)) {

            $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

            $post = $projects->getPost();
            
            $responseArray = array();

            for ($i = 0; $i < count($post); $i++) {
                $item = array(
                    "id" => $post[$i]->getId(),
                    "title" => $post[$i]->getTitle(),
                    "postPictureUrl" => $post[$i]->getPostPictureUrl(),
                    "postTitle" => $post[$i]->getTitle(),
                    "postText" => $post[$i]->getPostText(),
                    "created_at" => $post[$i]->getCreatedAt()
                );
                array_push($responseArray, $item);
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
        }

        return new JsonResponse(null);
    }

    public function hasDonated($projectId){
        //anzahl der ProjectIds checken

        //if ( $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {

        $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
        $donations = $repository->getDonations();

        $user = $this->getUser();

        foreach($donations as $donation){
            if($donation->getUsers()->getId() == $user->getId()){
                return true;
            }
        }

        return false;

        //}

        return null;

    }
    
    public function createAction($projectId, Request $request)
    {

        //if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $form = $this->createFormBuilder()
                ->add('title')
                ->add('postPictureUrl')
                ->add('postText')
                ->getForm();

            if ($request->isMethod('POST')) {

                $form->submit($request->request->all($form->getName()));

                if ($form->isSubmitted()) {

                    $date = new \DateTime('now');

                    // data is an array with "phone" and "period" keys
                    $data = $form->getData();

                    $em = $this->getDoctrine()->getManager();

                    $post = new Post();
                    $post->setTitle($data['title']);
                    $post->setPostPictureUrl($data['postPictureUrl']);
                    $post->setPostText($data['postText']);
                    $post->setCreatedAt($date);
                    $post->setProjects($this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId));


                    $em = $this->getDoctrine()->getManager();

                    // tells Doctrine you want to (eventually) save the Product (no queries yet)
                    $em->persist($post);

                    // actually executes the queries (i.e. the INSERT query)
                    $em->flush();

                    return new JsonResponse('Inserted Post successful');
                }
                return false;
            }
            return null;
        //}
        return null;
    }




    public function updateAction($postId, Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $form = $this->createFormBuilder()
                ->add('title')
                ->add('postPictureUrl')
                ->add('postText')
                ->getForm();

            if ($request->isMethod('POST')) {

                $form->submit($request->request->all($form->getName()));

                if ($form->isSubmitted()) {

                    $date = new \DateTime('now');

                    // data is an array with "phone" and "period" keys
                    $data = $form->getData();

                    $em = $this->getDoctrine()->getManager();

                    $post = $em->getRepository('HtlSpendenportalBundle:Post')->find($postId);
                    $post->setTitle($data['title']);
                    $post->setPostPictureUrl($data['postPictureUrl']);
                    $post->setPostText($data['postText']);

                    $em = $this->getDoctrine()->getManager();

                    // actually executes the queries (i.e. the INSERT query)
                    $em->flush();

                    return new JsonResponse('Updated Post successful');
                }
                return false;
            }
            return null;
        }
    }
    
    public function deleteAction($postId)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {


            $em = $this->getDoctrine()->getManager();
            $post = $em->getRepository('HtlSpendenportalBundle:Post')->find($postId);

            if (!$post) {
                throw $this->createNotFoundException(
                    'No category found for id ' . $postId
                );
            }

            /* Schauen ob es für diesen Post childs existieren! */


            $em->remove($post);
            $em->flush();


            return new Response('Picture has been deleted!');
        }
        return new JsonResponse('not logged in');
    }
}
