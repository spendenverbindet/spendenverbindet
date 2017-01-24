<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Htl\SpendenportalBundle\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EmailController extends Controller
{
    public function listAction(){

        $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Email');
        $emails = $repository->findAll();

        $responseArray = array();

        for($i=0;$i<count($emails);$i++){
            $item = array("id"=>$emails[$i]->getId(), "email"=>$emails[$i]->getEmailAdresse());
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
            $email = new Email();
            $email->setEmailAdresse($categoryText);


            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($email);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new \Symfony\Component\HttpFoundation\Response('Inserted Email successful');
        }
    }

    public function updateAction($emailId,$newName){

        $em = $this->getDoctrine()->getManager();
        $email = $em->getRepository('HtlSpendenportalBundle:Email')->find($emailId);

        if (!$email) {
            throw $this->createNotFoundException(
                'No category found for id '.$emailId
            );
        }

        $email->setName($newName);

        $em->flush();

        return new Response('Updated category successful');
    }

    public function deleteAction($emailId){

        $em = $this->getDoctrine()->getManager();
        $email = $em->getRepository('HtlSpendenportalBundle:Category')->find($emailId);

        if (!$email) {
            throw $this->createNotFoundException(
                'No category found for id '.$emailId
            );
        }

        /* Schauen ob es für diese Category childs existieren! */


        $em->remove($email);
        $em->flush();


        return new Response('Category has been deleted!');
    }
}
