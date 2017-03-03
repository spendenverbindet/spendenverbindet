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
    public function updateAction(Request $request, $userId)
    {
        //if ( $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $form = $this->createFormBuilder()
                ->add('username')
                ->add('email')
                //->add('enabled')
                ->add('password')
                ->add('firstname')
                ->add('lastname')
                ->add('town')
                ->add('street')
                ->add('zipcode')
                ->add('year_of_birth')
                ->add('housenumber_doornumber')
                ->getForm();

            if ($request->isMethod('POST')) {

                $form->submit($request->request->all($form->getName()));
                //return new JsonResponse($request);

                if ($form->isSubmitted()) {

                    // data is an array with "phone" and "period" keys
                    $data = $form->getData();

                    $em = $this->getDoctrine()->getManager();

                    $user = $em->getRepository('HtlSpendenportalBundle:User')->find($userId);

                    $user->setUsername($data["username"]);
                    $user->setEmail($data["email"]);
                    //$user->setEnabled($data["enabled"]);
                    $user->setPassword($data["password"]);
                    $user->setFirstname($data["firstname"]);
                    $user->setLastname($data["lastname"]);
                    $user->setTown($data["town"]);
                    $user->setStreet($data["street"]);
                    $user->setZipcode($data["zipcode"]);
                    $user->setAge($data["year_of_birth"]);
                    $user->setHousenumberDoornumber($data["housenumber_doornumber"]);

                    // or this could be $contract = new Contract("John Doe", $data["phone"], $data["period"]);

                    $em->flush();

                    return $this->render('BackendAdminBundle::index.html.twig');
                }
            }
        //}
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
