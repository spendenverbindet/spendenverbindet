<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DonationController extends Controller
{
    public function ifDonatedAction($projectId){
        //anzahl der ProjectIds checken

        if ( $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {
            
        $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
        $donations = $repository->getDonations();

            $user = $this->getUser();

            foreach($donations as $donation){
                if($donation->getUsers()->getId() == $user->getId()){
                    return new JsonResponse(true);
                } else {
                    return new JsonResponse(false);
                }
            }

            return new JsonResponse(false);

        }

        return new JsonResponse(null);

    }

    public function listAction(){

        $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Follower');
        $donation = $repository->findAll();

        $responseArray = array();

        for($i=0;$i<count($donation);$i++){
            $item = array("id"=>$donation[$i]->getId(), "amount"=>$donation[$i]->getAmount(), "dateSent"=>$donation[$i]->getDateSent(), "dateReceived"=>$donation[$i]->getDateReceived(), "anonym"=>$donation[$i]->getAnonym(), "users"=>$donation[$i]->getUsers(), "projects"=>$donation[$i]->getProjects());
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }

    public function createAction ($projectId, $userId, $amount) {


        $projectId = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
        $userId = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find($userId);

        $date = new \DateTime('now');

        if (false) {
            throw $this->createNotFoundException(
                'No category found for id  or not User found for id '
            );
        }
        else{
            //$data["amount"] $data = $request->request->all();
            $donation = new Donation();
            $donation->setAmount($amount);
            $donation->setDateSent($date);
            $donation->setDateReceived($date);
            $donation->setAnonym(false);
            $donation->setProjects($projectId);
            $donation->setUsers($userId);
            $currentAmount = $projectId->getCurrentAmount();
            $currentDonators = $projectId->getCurrentDonators();
            $projectId->setCurrentAmount($currentAmount + $amount);
            $projectId->setCurrentAmount($currentDonators + 1);



            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($donation);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new \Symfony\Component\HttpFoundation\Response('Inserted Project successful').$donation->getId();
        }
    }

    public function updateAction($donationId,$newName){

        $em = $this->getDoctrine()->getManager();
        $donation = $em->getRepository('HtlSpendenportalBundle:Category')->find($donationId);

        if (!$donation) {
            throw $this->createNotFoundException(
                'No category found for id '.$donationId
            );
        }

        $donation->setName($newName);

        $em->flush();

        return new Response('Updated category successful');
    }

    public function deleteAction($donationId){

        $em = $this->getDoctrine()->getManager();
        $donation = $em->getRepository('HtlSpendenportalBundle:Donation')->find($donationId);

        if (!$donation) {
            throw $this->createNotFoundException(
                'No category found for id '.$donationId
            );
        }

        /* Schauen ob es für diese Donation childs existieren! */


        $em->remove($donation);
        $em->flush();


        return new Response('Category has been deleted!');
    }
}
