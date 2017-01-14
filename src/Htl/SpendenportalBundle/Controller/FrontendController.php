<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     * @Route("/createProduct")
     */
    public function insertProduct($title, $desciption, $shortinfo, $deadline, $categoryId, $user, $projectamount, $follower, $report, $donation, $post){

        $category = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($categoryId);
        $user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($user);
        $projectamount = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($projectamount);
        $follower = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($follower);
        $report = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($report);
        $donation = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($donation);
        $post = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($post);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id '.$categoryId
            );
        }
        else{

            $product = new Product();
            $product->setTitle($title);
            $product->setDescription($desciption);
            $product->setShortinfo($shortinfo);
            $product->setdeadline($deadline);
            $product->setCategory($category);
            $product->setUser($user);
            $product->setProjectamount($projectamount);
            $product->setFollower($follower);
            $product->setReport($report);
            $product->setdonation($donation);
            $product->setPost($post);


            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($product);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new Response('Saved new product with id '.$product->getId());
        }
    }
}
