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
            "role" => ($user->getRoles()[0] == "ROLE_DONATOR") ? "Spender" : "Empfänger",
            "BeduerftigkeitsbeweisFile" => $user->getFileUpload(),
            "firstname" => $user->getFirstname(),
            "lastname" => $user->getLastname(),
            "birthDate" => $user->getAge(),
            $birthDate = $this->GetAge($user->getAge()),
            //$today   = (new \DateTime('today'))->format('d-m-y'),
            "age" => $birthDate,
            "birthday" => $user->getAge()->format('Y-m-d'),
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
    
    public function ifAlreadyGivenAction(Request $request){
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER') || $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {    // && $this->getUser()->getProjects()->find($projectId)
            //$userId = $this->getUser()->getId();
            //$userId = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(1);

            $form = $this->createFormBuilder()
                ->add('username')
                ->add('email')
                ->getForm();

            if ($request->isMethod('POST')) {

                $form->submit($request->request->all($form->getName()));
                //return new JsonResponse($request);

                // data is an array with keys
                $data = $form->getData();
                $responseArray = array();
                if($this->getUser()->getUsername() != $data["username"] && $this->getUser()->getEmail() != $data["email"]){
                    if ($this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->findOneBy(array('username' => $data["username"])) && $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->findOneBy(array('email' => $data["email"]))) {
                        $item = array(
                            "username" => true,
                            "email" => true,
                        );
                        array_push($responseArray, $item);
                        $responseArray = (object)$responseArray;

                        return new JsonResponse($responseArray);
                    } else if ($this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->findOneBy(array('username' => $data["username"]))) {
                        $item = array(
                            "username" => true,
                            "email" => false,
                        );
                        array_push($responseArray, $item);

                        $responseArray = (object)$responseArray;

                        return new JsonResponse($responseArray);
                    } else if ($this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->findOneBy(array('email' => $data["email"]))) {
                        $item = array(
                            "username" => false,
                            "email" => true,
                        );
                        array_push($responseArray, $item);

                        $responseArray = (object)$responseArray;

                        return new JsonResponse($responseArray);
                    } else {
                        $item = array(
                            "username" => false,
                            "email" => false,
                        );
                        array_push($responseArray, $item);

                        $responseArray = (object)$responseArray;

                        return new JsonResponse($responseArray);
                    }
                } else if($this->getUser()->getUsername() == $data["username"] && $this->getUser()->getEmail() != $data["email"]){
                    if ($this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->findOneBy(array('email' => $data["email"]))) {
                        $item = array(
                            "username" => false,
                            "email" => true,
                        );
                        array_push($responseArray, $item);

                        $responseArray = (object)$responseArray;

                        return new JsonResponse($responseArray);
                    } else {
                        $item = array(
                            "username" => false,
                            "email" => false,
                        );
                        array_push($responseArray, $item);

                        $responseArray = (object)$responseArray;

                        return new JsonResponse($responseArray);
                    }
                } else if($this->getUser()->getUsername() != $data["username"] && $this->getUser()->getEmail() == $data["email"]){
                    if ($this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->findOneBy(array('username' => $data["username"]))) {
                        $item = array(
                            "username" => true,
                            "email" => false,
                        );
                        array_push($responseArray, $item);

                        $responseArray = (object)$responseArray;

                        return new JsonResponse($responseArray);
                    } else {
                        $item = array(
                            "username" => false,
                            "email" => false,
                        );
                        array_push($responseArray, $item);

                        $responseArray = (object)$responseArray;

                        return new JsonResponse($responseArray);
                    }
                }
                $item = array(
                    "username" => false,
                    "email" => false,
                );
                array_push($responseArray, $item);

                $responseArray = (object)$responseArray;

                return new JsonResponse($responseArray);

            }
            return new JsonResponse(false);
        }
        return new JsonResponse(false);

    }

/*
     * else if($this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->findOneBy(array('username' => $data["username"]))){
                    $responseArray = array();

                    $item = array(
                        "usernameTaken" => true,
                        "emailTaken" => false,
                    );
                    array_push($responseArray, $item);
                    }
                    $responseArray = (object)$responseArray;

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
                $user->setAge(date_create(date('Y-m-d', strtotime($data['age']))));
                $user->setTown($data["town"]);
                $user->setStreet($data["street"]);
                $user->setZipcode($data["zipcode"]);
                $user->setHousenumberDoornumber($data["housenumberDoornumber"]);
                if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER') || $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')){
                    //
                    //!$_FILES['fileUrl']['name']
                    if(!empty($_FILES)){
                        if($_FILES['fileUrl']['name']!="") {
                            //return new JsonResponse($_FILES);
                            //file upload
                            $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/bundles/htlspendenportal/img/';
                            $filename = trim(addslashes($_FILES['fileUrl']['name']));
                            $filename = preg_replace('/\s+/', '_', $filename);
                            $filename = md5(uniqid()).'_'.$filename;
                            $target_file = $target_dir . $filename;
                            $uploadOk = 1;

                            // Generate a unique name for the file before saving it
                            //$filename = md5(uniqid()).'.'.$filename;

                            //return new JsonResponse($user->getFileUpload());

                            if (unlink($target_dir . $user->getFileUpload())) {
                                $uploadOk = 1;
                            }

                            $user->setFileUpload($filename);

                            $pdfFileType = pathinfo($filename, PATHINFO_EXTENSION);
                            // Check if image file is a actual image or fake image
                            if (isset($_POST["submit"])) {
                                $check = getimagesize($_FILES["fileUrl"]["tmp_name"]);
                                if ($check !== false) {
                                    $uploadOk = 1;
                                } else {
                                    $uploadOk = 0;
                                    return new JsonResponse("File is not an image.");
                                }
                            }
                            // Check file size
                            if ($_FILES["fileUrl"]["size"] > 6000000) {
                                $uploadOk = 0;
                                return new JsonResponse("Sorry, your file is too large. Maximal 750kB");
                            }
                            /*
                            // Allow certain file formats
                            if ($pdfFileType != "pdf" && $pdfFileType != "PDF" && $pdfFileType != ""
                            ) {
                                $uploadOk = 0;
                                return new JsonResponse("Sorry, only PDF files are allowed.");
                            }
                            */
                            // Check if $uploadOk is set to 0 by an error
                            if ($uploadOk == 0) {
                                return new JsonResponse("Sorry, your file was not uploaded.");
                                // if everything is ok, try to upload file
                            } else {
                                if (move_uploaded_file($_FILES["fileUrl"]["tmp_name"], $target_file)) {
                                } else {
                                    return new JsonResponse("Sorry, there was an error uploading your file.".$_FILES["fileUrl"]["name"]." target_file".$target_file);
                                }
                            }
                        }
                        $em->flush();

                        if($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {
                            return $this->redirectToRoute('htl_spendenportal_mein_profil_empfaenger');
                        }
                    }
                }
                $em->flush();
                return $this->redirectToRoute('htl_spendenportal_mein_profil_spender');
            }

            return false;
        }
        return new JsonResponse("not logged in");
    }
}
