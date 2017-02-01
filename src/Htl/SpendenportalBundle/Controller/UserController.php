<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function listAllAction(){
        $user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->findAll();


        $responseArray = array();

        for($i=0;$i<count($user);$i++){
            $item = array(
                "id"=>$user[$i]->getId(),
                "username"=>$user[$i]->getUsername(),
                "usernameCanonical"=>$user[$i]->getUsernameCanonical(),
                "email"=>$user[$i]->getEmail(),
                "emailCanonical"=>$user[$i]->getEmailCanonical(),
                "enable"=>$user[$i]->getEnabled(),
                "password"=>$user[$i]->getPassword(),
                "role"=>$user[$i]->getRoles(),
                "mobil_pass_number"=>$user[$i]->getMobilPassNumber(),
                "firstname"=>$user[$i]->getFirstname(),
                "lastname"=>$user[$i]->getLastname(),
                "street"=>$user[$i]->getStreet(),
                "zipcode"=>$user[$i]->getZipcode(),
                "housenumber"=>$user[$i]->getHousenumber(),
            );
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }
    
    public function listSpecificAction($userId){
        $user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find($userId);
        
        $responseArray = array();

        for($i=0;$i<count($user);$i++){
            $item = array(
                "id"=>$user[$i]->getId(),
                "username"=>$user[$i]->getUsername(),
                "usernameCanonical"=>$user[$i]->getUsernameCanonical(),
                "email"=>$user[$i]->getEmail(),
                "emailCanonical"=>$user[$i]->getEmailCanonical(),
                "enable"=>$user[$i]->getEnabled(),
                "password"=>$user[$i]->getPassword(),
                "role"=>$user[$i]->getRoles(),
                "mobil_pass_number"=>$user[$i]->getMobilPassNumber(),
                "firstname"=>$user[$i]->getFirstname(),
                "lastname"=>$user[$i]->getLastname(),
                "street"=>$user[$i]->getStreet(),
                "zipcode"=>$user[$i]->getZipcode(),
                "housenumber"=>$user[$i]->getHousenumber(),
            );
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }
    
    public function createAction ($username, $usernameCanonical, $email, $emailCanonical, $password, $mobil_pass_number, $role, $firstname, $lastname, $street, $zipcode, $housnumber) {

        $date = new \DateTime('now');

        if (false) {
            throw $this->createNotFoundException(
                'No category found for id  or not User found for id '
            );
        }
        else{

            $user = new User();
            $user->setUsername($username);
            $user->setUsernameCanonical($usernameCanonical);
            $user->setEmail($email);
            $user->setEmailCanonical($emailCanonical);
            $user->setEnabled(true);
            $user->setPassword($password);
            $user->setLastLogin($date);
            $user->addRole(array(''.$role.''));
            //$user->setIsDonator(true);
            $user->setMobilPassNumber($mobil_pass_number);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setStreet($street);
            $user->setZipcode($zipcode);
            $user->setHousenumber($housnumber);

            
            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($user);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new \Symfony\Component\HttpFoundation\Response('Inserted User successfully');
        }
    }

    public function updateAction($userId, $username, $usernameCanonical, $email, $emailCanonical, $enable, $password, $mobil_pass_number, $role, $firstname, $lastname, $street, $zipcode, $housnumber){

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('HtlSpendenportalBundle:User')->find($userId);

        if (!$user) {
            throw $this->createNotFoundException(
                'No category found for id '.$userId
            );
        }

        $user->setUsername($username);
        $user->setUsernameCanonical($usernameCanonical);
        $user->setEmail($email);
        $user->setEmailCanonical($emailCanonical);
        $user->setEnabled($enable);
        $user->setPassword($password);
        $user->addRole(array(''.$role.''));
        //$user->setIsDonator(true);
        $user->setMobilPassNumber($mobil_pass_number);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setStreet($street);
        $user->setZipcode($zipcode);
        $user->setHousenumber($housnumber);

        $em->flush();

        return new Response('Updated user successful');
    }

    public function deleteAction($userId){

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('HtlSpendenportalBundle:Category')->find($userId);

        if (!$user) {
            throw $this->createNotFoundException(
                'No category found for id '.$userId
            );
        }

        /* Schauen ob es für diesen Post childs existieren! */


        $em->remove($user);
        $em->flush();


        return new Response('Picture has been deleted!');
    }
}
