<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;

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
                "BeduerftigkeitsbeweisFile"=>$user[$i]->getFileUpload(),
                "firstname"=>$user[$i]->getFirstname(),
                "lastname"=>$user[$i]->getLastname(),
                "street"=>$user[$i]->getStreet(),
                "zipcode"=>$user[$i]->getZipcode(),
                "housenumberDoornumber"=>$user[$i]->getHousenumberDoornumber(),
            );
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }

    public function listAllBackendAction(){
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
                "role"=> ($user[$i]->getRoles()[0] == "ROLE_DONATOR") ? "Spender" : "Empfänger",
                "BeduerftigkeitsbeweisFile"=>$user[$i]->getFileUpload(),
                "firstname"=>$user[$i]->getFirstname(),
                "lastname"=>$user[$i]->getLastname(),
                "street"=>$user[$i]->getStreet(),
                $birthDate = $this->GetAge($user[$i]->getAge()),
                //$today   = (new \DateTime('today'))->format('d-m-y'),
                "age" => $birthDate,"zipcode"=>$user[$i]->getZipcode(),
                "housenumberDoornumber"=>$user[$i]->getHousenumberDoornumber(),
                "amountProjects"=>count($user[$i]->getProjects()),
                "currentAmount"=>$user[$i]->getProjects()[0]
            );
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }

    public function GetAge($dob)
    {
        $dob=explode("-",$dob);
        $curMonth = date("m");
        $curDay = date("j");
        $curYear = date("Y");
        $age = $curYear - $dob[0];
        if($curMonth<$dob[1] || ($curMonth==$dob[1] && $curDay<$dob[2]))
            $age--;
        return $age;
    }
    
    public function listSpecificAction($userId){
        $user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find($userId);
        
        $responseArray = array();


            $item = array(
                "id"=>$user->getId(),
                "username"=>$user->getUsername(),
                "usernameCanonical"=>$user->getUsernameCanonical(),
                "email"=>$user->getEmail(),
                "emailCanonical"=>$user->getEmailCanonical(),
                "enable"=>$user->getEnabled(),
                "password"=>$user->getPassword(),
                "role"=>$user->getRoles(),
                "BeduerftigkeitsbeweisFile"=>$user->getFileUpload(),
                "firstname"=>$user->getFirstname(),
                "lastname"=>$user->getLastname(),
                "street"=>$user->getStreet(),
                "zipcode"=>$user->getZipcode(),
                "housenumberDoornumber"=>$user->getHousenumberDoornumber(),
            );
            array_push($responseArray, $item);


        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }
    
    public function createAction ($username, $usernameCanonical, $email, $emailCanonical, $password, $BeduerftigkeitsbeweisFile, $role, $firstname, $lastname, $street, $zipcode, $housenumberDoornumber) {

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
            $user->setFileUpload($BeduerftigkeitsbeweisFile);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setStreet($street);
            $user->setZipcode($zipcode);
            $user->setHousenumberDoornumber($housenumberDoornumber);

            
            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($user);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new \Symfony\Component\HttpFoundation\Response('Inserted User successfully');
        }
    }

    public function updateAction($userId, $username, $usernameCanonical, $email, $emailCanonical, $enable, $password, $BeduerftigkeitsbeweisFile, $role, $firstname, $lastname, $street, $zipcode, $housenumberDoornumber){

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
        $user->setFileUpload($BeduerftigkeitsbeweisFile);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setStreet($street);
        $user->setZipcode($zipcode);
        $user->setHousenumberDoornumber($housenumberDoornumber);

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
