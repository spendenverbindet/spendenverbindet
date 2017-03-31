<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Htl\SpendenportalBundle\Entity\Post;

class PostController extends Controller
{
    public function listFromProjectAction($projectId){

        if ($this->get('security.authorization_checker')->isGranted('ROLE_DONATOR') && $this->hasDonated($projectId)) {
            $security = true;
        }else{
            $security = false;
        }
        if($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER') || $security){



            $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

            $post = $projects->getPost();
            
            $responseArray = array();

            for ($i = 0; $i < count($post); $i++) {
                $item = array(
                    "id" => $post[$i]->getId(),
                    "title" => $post[$i]->getTitle(),
                    "postPictureUrl" => $post[$i]->getPostPictureUrl(),
                    "postTitle" => $post[$i]->getTitle(),
                    "postText" => $post[$i]->getPostText(),
                    "created_at" => $post[$i]->getCreatedAt()
                );
                array_push($responseArray, $item);
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
        }

        return new JsonResponse(null);
    }

    public function listFromActiveProjectAction(){
        $projects = $this->getUser()->getProjects();
        foreach($projects as $project){
            if($project->getActive()){
                $projectId  = $project->getId();
                if ($this->get('security.authorization_checker')->isGranted('ROLE_DONATOR') && $this->hasDonated($projectId)) {
                    $security = true;
                }else{
                    $security = false;
                }
                if($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER') || $security){



                    $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

                    $post = $projects->getPost();

                    $responseArray = array();

                    for ($i = 0; $i < count($post); $i++) {
                        $item = array(
                            "id" => $post[$i]->getId(),
                            "title" => $post[$i]->getTitle(),
                            "postPictureUrl" => $post[$i]->getPostPictureUrl(),
                            "postTitle" => $post[$i]->getTitle(),
                            "postText" => $post[$i]->getPostText(),
                            "created_at" => $post[$i]->getCreatedAt()
                        );
                        array_push($responseArray, $item);
                    }

                    $responseArray = (object)$responseArray;

                    return new JsonResponse($responseArray);
                }
            }
        }
        return new JsonResponse("fail");
    }

    public function hasDonated($projectId){
        //anzahl der ProjectIds checken

        //if ( $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {

        $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
        $donations = $repository->getDonations();

        $user = $this->getUser();

        foreach($donations as $donation){
            if($donation->getUsers()->getId() == $user->getId()){
                return true;
            }
        }

        return false;

        //}

        return null;

    }
    
    public function createAction(Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $form = $this->createFormBuilder()
                ->add('title')
                ->add('postPictureUrl')
                ->add('postText')
                ->getForm();

            if ($request->isMethod('POST')) {

                $form->submit($request->request->all($form->getName()));

                if ($form->isSubmitted()) {

                    $projects = $this->getUser()->getProjects();

                    foreach ($projects as $project) {
                        if ($project->getActive()) {
                            $projectId = $project->getId();
                            $date = new \DateTime('now');

                            // data is an array with "phone" and "period" keys
                            $data = $form->getData();

                            $em = $this->getDoctrine()->getManager();

                            $post = new Post();
                            $post->setTitle($data['title']);
                            $post->setPostText($data['postText']);
                            $post->setCreatedAt($date);
                            $post->setProjects($this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId));

                            $rand = rand(1, 300);
                            $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/bundles/htlspendenportal/img/';
                            $filename = trim(addslashes($_FILES['postPictureUrl']['name']));
                            $filename = $rand . preg_replace('/\s+/', '_', $filename);
                            $target_file = $target_dir . $filename;
                            $post->setPostPictureUrl($filename);
                            $uploadOk = 1;
                            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                            // Check if image file is a actual image or fake image
                            if (isset($_POST["submit"])) {
                                $check = getimagesize($_FILES["postPictureUrl"]["tmp_name"]);
                                if ($check !== false) {
                                    $uploadOk = 1;
                                } else {
                                    $uploadOk = 0;
                                    return new JsonResponse("File is not an image.");
                                }
                            }
                            // Check file size
                            if ($_FILES["postPictureUrl"]["size"] > 6000000) {
                                $uploadOk = 0;
                                return new JsonResponse("Sorry, your file is too large. Maximal 750kB");
                            }
                            // Allow certain file formats

                            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                                && $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG"
                                && $imageFileType != "GIF" && $imageFileType != ""
                            ) {
                                $uploadOk = 0;
                                return new JsonResponse("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                            }

                            // Check if $uploadOk is set to 0 by an error
                            if ($uploadOk == 0) {
                                return new JsonResponse("Sorry, your file was not uploaded.");
                                // if everything is ok, try to upload file
                            } else {
                                if (move_uploaded_file($_FILES["postPictureUrl"]["tmp_name"], $target_file)) {
                                } else {
                                    return new JsonResponse("Sorry, there was an error uploading your file.");
                                }
                            }


                            $em = $this->getDoctrine()->getManager();

                            // tells Doctrine you want to (eventually) save the Product (no queries yet)
                            $em->persist($post);

                            // actually executes the queries (i.e. the INSERT query)
                            $em->flush();

                            return $this->redirectToRoute('htl_spendenportal_projekt', array('projectName' => $project->getTitle(),'projectId' => $projectId));
                            return $this->redirectToRoute('htl_spendenportal_projekt_bearbeiten');
                            return $this->redirectToRoute('htl_spendenportal_projekt');
                        }
                    }
                }
                return false;
            }
            return null;
        }
        return null;
    }




    public function updateAction($postId, Request $request)
    {

        //if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $form = $this->createFormBuilder()
                ->add('title')
                ->add('postPictureUrl')
                ->add('postText')
                ->getForm();

            if ($request->isMethod('POST')) {

                $form->submit($request->request->all($form->getName()));

                if ($form->isSubmitted()) {

                    $date = new \DateTime('now');

                    // data is an array with "phone" and "period" keys
                    $data = $form->getData();

                    $em = $this->getDoctrine()->getManager();

                    $post = $em->getRepository('HtlSpendenportalBundle:Post')->find($postId);
                    $post->setTitle($data['title']);
                    $post->setPostText($data['postText']);

                    if (!$_FILES['titlePictureUrl']['name'] == "") {
                        $post->setPostPictureUrl($_FILES['postPictureUrl']['name']);
                        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/bundles/htlspendenportal/img/';
                        $filename = trim(addslashes($_FILES['postPictureUrl']['name']));
                        $filename = preg_replace('/\s+/', '_', $filename);
                        $filename = md5(uniqid()).'_'.$filename;
                        $target_file = $target_dir . $filename;
                        $uploadOk = 1;
                        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                        // Check if image file is a actual image or fake image
                        if (isset($_POST["submit"])) {
                            $check = getimagesize($_FILES["postPictureUrl"]["tmp_name"]);
                            if ($check !== false) {
                                $uploadOk = 1;
                            } else {
                                $uploadOk = 0;
                                return new JsonResponse("File is not an image.");
                            }
                        }
                        // Check file size
                        if ($_FILES["postPictureUrl"]["size"] > 6000000) {
                            $uploadOk = 0;
                            return new JsonResponse("Sorry, your file is too large. Maximal 750kB");
                        }
                        // Allow certain file formats
                        /*
                        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                            && $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG"
                            && $imageFileType != "GIF" && $imageFileType != ""
                        ) {
                            $uploadOk = 0;
                            return new JsonResponse("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                        }
                        */
                        // Check if $uploadOk is set to 0 by an error
                        if ($uploadOk == 0) {
                            return new JsonResponse("Sorry, your file was not uploaded.");
                            // if everything is ok, try to upload file
                        } else {
                            if (move_uploaded_file($_FILES["postPictureUrl"]["tmp_name"], $target_file)) {
                            } else {
                                return new JsonResponse("Sorry, there was an error uploading your file.");
                            }
                        }

                        // actually executes the queries (i.e. the INSERT query)
                        $em->flush();

                        return new JsonResponse('Updated Post successful');
                    }
                }
                return false;
            }
            return null;
        //}
    }
    
    public function deleteAction($postId)
    {
        //if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {
            $user = $this->getUser();
            //$user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(5);
        
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/bundles/htlspendenportal/img/';


            $em = $this->getDoctrine()->getManager();
            $post = $em->getRepository('HtlSpendenportalBundle:Post')->find($postId);

            if (!$post) {
                throw $this->createNotFoundException(
                    'No category found for id ' . $postId
                );
            }

            /* Schauen ob es für diesen Post childs existieren! */
            if (unlink($target_dir . $post->getPostPictureUrl())) {
                $uploadOk = 1;
            }
        
            $em->remove($post);
            $em->flush();


            return new JsonResponse('Picture has been deleted!');
        //}
        return new JsonResponse('not logged in');
    }
}
