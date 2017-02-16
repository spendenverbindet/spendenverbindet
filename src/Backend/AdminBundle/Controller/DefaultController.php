<?php

namespace Backend\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        
        $persons = $this->getDoctrine()
            ->getRepository('HtlSpendenportalBundle:User')->findAll();

        
        //var_dump($persons);

        if (!$persons) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }


        return $this->render('BackendAdminBundle::index.html.twig', array(
            'persons' => $persons,
        ));
        
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

    public function editAction()
    {
        return $this->render('BackendAdminBundle::editUser.html.twig');
    }
}
