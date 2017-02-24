<?php

namespace Backend\AdminBundle\Controller;

use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\DateTimeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function updateAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('enabled', BooleanType::class)
            ->add('password', PasswordType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('town', TextType::class)
            ->add('street', TextType::class)
            ->add('zipcode', NumberType::class)
            ->add('year_of_birth', DateTimeType::class)
            ->add('housenumber_doornumber', TextType::class)
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);

            // data is an array with "phone" and "period" keys
            $data = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository('HtlSpendenportalBundle:User');
            $user->setUsername("John Doe");
            $user->setPhone($data["phone"]);

            $user->setUsername($data["username"]);
            $user->setEmail($data["email"]);
            $user->setEnabled($data["enabled"]);
            $user->setPassword($data["password"]);
            $user->setFirstname($data["firstname"]);
            $user->setLastname($data["lastname"]);
            $user->setTown($data["town"]);
            $user->setStreet($data["street"]);
            $user->setZipcode($data["zipcode"]);
            $user->setAge($data["year_of_birth"]);
            $user->setHousenumberDoornumber($data["housenumber_doornumber"]);

            // or this could be $contract = new Contract("John Doe", $data["phone"], $data["period"]);

            $em->persist($user); // I set/modify the properties then persist
        }
    }

    public function indexAction()
    {
        
        $users = $this->getDoctrine()
            ->getRepository('HtlSpendenportalBundle:User')->findAll();

        
        //var_dump($persons);

        if (!$users) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }


        return $this->render('BackendAdminBundle::index.html.twig', array(
            'persons' => $users,
        ));
        
    }

    public function showAction($userId){
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
            "age"=>$user->getAge(),
            "firstname"=>$user->getFirstname(),
            "lastname"=>$user->getLastname(),
            "street"=>$user->getStreet(),
            "town"=>$user->getTown(),
            "zipcode"=>$user->getZipcode(),
            "housenumberDoornumber"=>$user->getHousenumberDoornumber(),
        );
        array_push($responseArray, $item);


        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }

    public function renderEditAction()
    {
        return $this->render('BackendAdminBundle::editUser.html.twig');
    }

    public function userProjectsAction()
    {
        return $this->render('BackendAdminBundle::userProjects.html.twig');
    }

    public function projectFollowerAction()
    {
        return $this->render('BackendAdminBundle::listFollowers.html.twig');
    }
}
