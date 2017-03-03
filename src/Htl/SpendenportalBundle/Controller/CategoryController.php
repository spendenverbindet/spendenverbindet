<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Htl\SpendenportalBundle\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Htl\SpendenportalBundle\Entity\Category;

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

    public function createAction (Request $request) {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $form = $this->createFormBuilder()
                ->add('categoryText')
                ->getForm();

            if ($request->isMethod('POST')) {

                $form->submit($request->request->all($form->getName()));

                if ($form->isSubmitted()) {

                    $data = $form->getData();

                    //$data = $request->request->all(); $data["url"]
                    $category = new Category();
                    $category->setCategoryText($data['categoryText']);


                    $em = $this->getDoctrine()->getManager();

                    // tells Doctrine you want to (eventually) save the Product (no queries yet)
                    $em->persist($category);

                    // actually executes the queries (i.e. the INSERT query)
                    $em->flush();

                    return new JsonResponse('Inserted Category successful');
                }
                return false;
            }
            return false;
        }
        return new JsonResponse('not logged in');
    }

    public function updateAction($categoryId, Request $request){

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $form = $this->createFormBuilder()
                ->add('categoryText')
                ->getForm();

            if ($request->isMethod('POST')) {

                $form->submit($request->request->all($form->getName()));

                if ($form->isSubmitted()) {

                    $data = $form->getData();

                    $em = $this->getDoctrine()->getManager();
                    $category = $em->getRepository('HtlSpendenportalBundle:Category')->find($categoryId);

                    if (!$category) {
                        throw $this->createNotFoundException(
                            'No category found for id ' . $categoryId
                        );
                    }

                    $category->setCategoryText($data['categoryText']);

                    $em->flush();

                    return new JsonResponse('Updated category successful');
                }
                return false;
            }
            return false;
        }
        return new JsonResponse('not logged in');
    }

    public function deleteAction($categoryId){
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $em = $this->getDoctrine()->getManager();
            $category = $em->getRepository('HtlSpendenportalBundle:Category')->find($categoryId);

            if (!$category) {
                throw $this->createNotFoundException(
                    'No category found for id ' . $categoryId
                );
            }

            /* Schauen ob es für diese Category childs existieren! */


            $em->remove($category);
            $em->flush();


            return new Response('Category has been deleted!');
        }
        return new JsonResponse('not logged in');
    }
}
