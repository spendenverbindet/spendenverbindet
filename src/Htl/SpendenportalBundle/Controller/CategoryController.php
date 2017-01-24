<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Htl\SpendenportalBundle\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CategoryController extends Controller
{
    public function listAction(){

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

    public function createAction ($categoryText) {

        if (false) {
            throw $this->createNotFoundException(
                'No category found for id  or not User found for id '
            );
        }
        else{

            //$data = $request->request->all(); $data["url"]
            $category = new Category();
            $category->setCategoryText($categoryText);


            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($category);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new \Symfony\Component\HttpFoundation\Response('Inserted Category successful');
        }
    }

    public function updateAction($categoryId,$newName){

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('HtlSpendenportalBundle:Category')->find($categoryId);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id '.$categoryId
            );
        }

        $category->setName($newName);

        $em->flush();

        return new Response('Updated category successful');
    }

    public function deleteAction($categoryId){

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('HtlSpendenportalBundle:Category')->find($categoryId);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id '.$categoryId
            );
        }

        /* Schauen ob es für diese Category childs existieren! */


        $em->remove($category);
        $em->flush();


        return new Response('Category has been deleted!');
    }
}
