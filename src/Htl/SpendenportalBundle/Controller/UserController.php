<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;
use Htl\SpendenportalBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function listAllAction()
    {
        $user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->findAll();


        $responseArray = array();

        for ($i = 0; $i < count($user); $i++) {
            $item = array(
                "id" => $user[$i]->getId(),
                "username" => $user[$i]->getUsername(),
                "usernameCanonical" => $user[$i]->getUsernameCanonical(),
                "email" => $user[$i]->getEmail(),
                "emailCanonical" => $user[$i]->getEmailCanonical(),
                "enable" => $user[$i]->getEnabled(),
                "password" => $user[$i]->getPassword(),
                "role" => $user[$i]->getRoles(),
                "BeduerftigkeitsbeweisFile" => $user[$i]->getFileUpload(),
                "firstname" => $user[$i]->getFirstname(),
                "lastname" => $user[$i]->getLastname(),
                "street" => $user[$i]->getStreet(),
                //"town"=>$user->getTown(),
                "zipcode" => $user[$i]->getZipcode(),
                "housenumberDoornumber" => $user[$i]->getHousenumberDoornumber(),
            );
            array_push($responseArray, $item);
        }

        $responseArray = (object)$responseArray;

        return new JsonResponse($responseArray);
    }

    public function listAllBackendAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->findAll();

            $responseArray = array();

            for ($i = 0; $i < count($user); $i++) {

                $currentAmount = 0;

                /* @var \Htl\SpendenportalBundle\Entity\Project $project */
                foreach ($user[$i]->getProjects() as $project) {
                    $currentAmount += $project->getCurrentAmount();
                }

                if ($user[$i]->getRoles()[0] == "ROLE_DONATOR") {
                    $userRole = "Spender";
                } elseif ($user[$i]->getRoles()[0] == "ROLE_RECEIVER") {
                    $userRole = "Empfänger";
                } else {
                    $userRole = "Admin";
                }

                $item = array(
                    "id" => $user[$i]->getId(),
                    "username" => $user[$i]->getUsername(),
                    "usernameCanonical" => $user[$i]->getUsernameCanonical(),
                    "email" => $user[$i]->getEmail(),
                    "emailCanonical" => $user[$i]->getEmailCanonical(),
                    "enable" => $user[$i]->getEnabled(),
                    "password" => $user[$i]->getPassword(),
                    "role" => $userRole,
                    "BeduerftigkeitsbeweisFile" => $user[$i]->getFileUpload(),
                    "firstname" => $user[$i]->getFirstname(),
                    "lastname" => $user[$i]->getLastname(),
                    "street" => $user[$i]->getStreet(),
                    $birthDate = $this->GetAge($user[$i]->getAge()),
                    //$today   = (new \DateTime('today'))->format('d-m-y'),
                    "age" => $birthDate,
                    "town" => $user[$i]->getTown(),
                    "zipcode" => $user[$i]->getZipcode(),
                    "housenumberDoornumber" => $user[$i]->getHousenumberDoornumber(),
                    "amountProjects" => count($user[$i]->getProjects()),
                    "currentAmount" => $currentAmount,
                    "lastLogin" => $user[$i]->getLastLogin()->format('d.m.y'),
                );
                array_push($responseArray, $item);
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
        }
        return new JsonResponse('not logged in');
    }

    public function GetAge($dob)
    {
        return 19;
        $dob = explode("-", $dob['date']);
        $curMonth = date("m");
        $curDay = date("j");
        $curYear = date("Y");
        $age = $curYear - $dob[0];
        if ($curMonth < $dob[1] || ($curMonth == $dob[1] && $curDay < $dob[2]))
            $age--;
        return $age;
    }

    public function listSpecificAction()
    {
        $userId = $this->getUser()->getId();
        $user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find($userId);

        $responseArray = array();

        $beweisfile = null;

        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {
            $beweisfile = $user->getFileUpload();
        }

        $item = array(
            "id" => $user->getId(),
            "username" => $user->getUsername(),
            "usernameCanonical" => $user->getUsernameCanonical(),
            "email" => $user->getEmail(),
            "emailCanonical" => $user->getEmailCanonical(),
            "enable" => $user->getEnabled(),
            "password" => $user->getPassword(),
            "role" => ($user->getRoles()[0] == "ROLE_DONATOR") ? "Spender" : "Empfänger",
            "BeduerftigkeitsbeweisFile" => $user->getFileUpload(),
            "firstname" => $user->getFirstname(),
            "lastname" => $user->getLastname(),
            "birthDate" => $user->getAge(),
            $birthDate = $this->GetAge($user->getAge()),
            //$today   = (new \DateTime('today'))->format('d-m-y'),
            "age" => $birthDate,
            "town" => $user->getTown(),
            "street" => $user->getStreet(),
            "zipcode" => $user->getZipcode(),
            "housenumberDoornumber" => $user->getHousenumberDoornumber(),
            "file" => $beweisfile,
        );
        array_push($responseArray, $item);

        $responseArray = (object)$responseArray;

        return new JsonResponse($responseArray);
    }

    public function listProjectsFromUserAction($userId)
    {
        $user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find($userId);

        $responseArray = array();

        /* @var \Htl\SpendenportalBundle\Entity\Project $project */
        foreach ($user->getProjects() as $project) {
            $test = 0;
            $item = array(
                "id" => $project->getId(),
                "title" => $project->getTitle(),
                "targetAmount" => $project->getTargetAmount(),
                "currentAmount" => $project->getCurrentAmount(),
                "currentDonators" => $project->getCurrentDonators(),
                "category" => $project->getCategory()->getCategoryText(),
            );
            array_push($responseArray, $item);
        }


        $responseArray = (object)$responseArray;

        return new JsonResponse($responseArray);
    }
    /*
    public function createAction ($username, $usernameCanonical, $email, $emailCanonical, $password, $BeduerftigkeitsbeweisFile, $role, $firstname, $lastname, $town, $street, $zipcode, $housenumberDoornumber) {
        

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
            $user->setTown($town);
            $user->setStreet($street);
            $user->setZipcode($zipcode);
            $user->setHousenumberDoornumber($housenumberDoornumber);

            $form = $this->createFormBuilder($user)
                ->add('username', TextType::class)
                ->add('dueDate', DateType::class)
                ->add('save', SubmitType::class, array('label' => 'Create Post'))
                ->getForm();
            
            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($user);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new \Symfony\Component\HttpFoundation\Response('Inserted User successfully');
        }
    }
    */
    /*
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

        // Schauen ob es für diesen Post childs existieren! /


        $em->remove($user);
        $em->flush();


        return new Response('Picture has been deleted!');
    }
    */

    public function updateAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER') || $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {    // && $this->getUser()->getProjects()->find($projectId)
            $userId = $this->getUser()->getId();

            $form = $this->createFormBuilder()
                ->add('username')
                ->add('email')
                ->add('firstname')
                ->add('lastname')
                ->add('age')
                ->add('town')
                ->add('street')
                ->add('zipcode')
                ->add('housenumberDoornumber')
                //->add('fileUrl')
                ->getForm();

            if ($request->isMethod('POST')) {

                $form->submit($request->request->all($form->getName()));
                //return new JsonResponse($request);

                if ($form->isSubmitted()) {

                    /*
                    if($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')){
                    $beweisfile = $user->getFileUpload();
                    */
                }

                // data is an array with "phone" and "period" keys
                $data = $form->getData();

                $em = $this->getDoctrine()->getManager();

                $user = $em->getRepository('HtlSpendenportalBundle:User')->find($userId);
                $user->setUsername($data["username"]);
                $user->setEmail($data["email"]);
                $user->setFirstname($data["firstname"]);
                $user->setLastname($data["lastname"]);
                //$user->setAge($data["age"]);
                $user->setTown($data["town"]);
                $user->setStreet($data["street"]);
                $user->setZipcode($data["zipcode"]);
                $user->setHousenumberDoornumber($data["housenumberDoornumber"]);

                $em->flush();

                if($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {
                    return $this->redirectToRoute('htl_spendenportal_mein_profil_empfaenger');
                }
                return $this->redirectToRoute('htl_spendenportal_mein_profil_spender');
            }

            return false;
        }
        return new JsonResponse("not logged in");
    }
}
