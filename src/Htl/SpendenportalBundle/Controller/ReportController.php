<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ReportController extends Controller
{
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



            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($donation);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new \Symfony\Component\HttpFoundation\Response('Inserted Project successful').$donation->getId();
        }
    }
}
