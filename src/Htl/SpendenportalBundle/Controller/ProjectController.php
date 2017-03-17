<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Htl\SpendenportalBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Request;
use Htl\SpendenportalBundle\Entity\Picture;
use Htl\SpendenportalBundle\Controller\PictureController;

class ProjectController extends Controller
{
    /*
    public function testAction(){
        $product = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->findOneBy(
            array('categoryText' => 'Bildung')
        );

        $categorys = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find(1)->getCategoryText();
        
        return new JsonResponse($product->getCategoryText());
    }
    */
    
    public function listAction(){
            $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->findAll();

            $responseArray = array();

            $hasDonated = null;

            foreach ($projects as $project) {
                if($project->getActive() && !$project->getDeleted()) {
                    if ($this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {
                        $hasDonated = $this->hasDonated($project->getId());
                    }

                    $progress = ($project->getTargetAmount() == 0) ? 0 : floor(($project->getCurrentAmount() / $project->getTargetAmount()) * 100);

                    $item = array(
                        "id" => $project->getId(),
                        "title" => $project->getTitle(),
                        "titlePictureUrl" => $project->getTitlePictureUrl(),
                        "description" => $project->getDescription(),
                        "shortinfo" => $project->getShortinfo(),
                        "created_at" => $project->getCreatedAt()->format('d.m.Y'),
                        "targetAmount" => $project->getTargetAmount(),
                        "currentAmount" => $project->getCurrentAmount(),
                        "deleted" => $project->getDeleted(),
                        "progress" => $progress,
                        "currentDonators" => $project->getCurrentDonators(),
                        "hasDonated" => $hasDonated
                    );
                    array_push($responseArray, $item);
                }
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
    }

    public function listMyActiveAction(){
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $projects = $this->getUser()->getProjects();

            $categories = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->findAll();

            //$projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(5)->getProjects();

            $responseArray = array();

            foreach ($projects as $project) {
                if ($project->getActive() && !$project->getDeleted()) {
                    $progress = ($project->getTargetAmount() == 0) ? 0 : floor(($project->getCurrentAmount() / $project->getTargetAmount()) * 100);
                    $item = array(
                        "id" => $project->getId(),
                        "title" => $project->getTitle(),
                        "titlePictureUrl" => $project->getTitlePictureUrl(),
                        "description" => $project->getDescription(),
                        "descriptionPrivate" => $project->getDescriptionPrivate(),
                        "shortinfo" => $project->getShortinfo(),
                        "created_at" => $project->getCreatedAt()->format('d.m.Y'),
                        "targetAmount" => $project->getTargetAmount(),
                        "currentAmount" => $project->getCurrentAmount(),
                        "deleted" => $project->getDeleted(),
                        "progress" => $progress,
                        "currentDonators" => $project->getCurrentDonators(),
                        "category" => $project->getCategory()->getCategoryText()
                    );
                    array_push($responseArray, $item);
                }
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
        }
        return new JsonResponse("no active project");
    }

    public function listMyFinishedAction(){
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $projects = $this->getUser()->getProjects();

            //$projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(3)->getProjects();

            $responseArray = array();

            foreach ($projects as $project) {
                if (!$project->getActive()) {
                    $progress = ($project->getTargetAmount() == 0) ? 0 : floor(($project->getCurrentAmount() / $project->getTargetAmount()) * 100);
                    $item = array(
                        "id" => $project->getId(),
                        "title" => $project->getTitle(),
                        "titlePictureUrl" => $project->getTitlePictureUrl(),
                        "description" => $project->getDescription(),
                        "shortinfo" => $project->getShortinfo(),
                        "created_at" => $project->getCreatedAt()->format('d.m.Y'),
                        "targetAmount" => $project->getTargetAmount(),
                        "currentAmount" => $project->getCurrentAmount(),
                        "deleted" => $project->getDeleted(),
                        "progress" => $progress,
                        "currentDonators" => $project->getCurrentDonators(),
                    );
                    array_push($responseArray, $item);
                }
            }

            $responseArray = (object)$responseArray;

            if(empty($responseArray)){
                return null;
            }

            return new JsonResponse($responseArray);
        }
        return null;
    }

    public function listBackendAction(){
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->findAll();

            $responseArray = array();

            foreach ($projects as $project) {
                if ($project->getTargetAmount() == 0) {
                    $progress = 0;
                } else {
                    $progress = floor(($project->getCurrentAmount() / $project->getTargetAmount()) * 100);
                }
                $item = array(
                    "id" => $project->getId(),
                    "title" => $project->getTitle(),
                    "active" => $project->getActive(),
                    "titlePictureUrl" => $project->getTitlePictureUrl(),
                    "description" => $project->getDescription(),
                    "descriptionPrivate" => $project->getDescriptionPrivate(),
                    "shortinfo" => $project->getShortinfo(),
                    "created_at" => $project->getCreatedAt()->format('d.m.Y'),
                    "targetAmount" => $project->getTargetAmount(),
                    "currentAmount" => $project->getCurrentAmount(),
                    "deleted" => $project->getDeleted(),
                    "progress" => $progress,
                    "currentDonators" => $project->getCurrentDonators(),
                    "username" => $project->getUsers()->getUsername(),
                    "category" => $project->getCategory()->getCategoryText()
                );
                array_push($responseArray, $item);
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
        }
        return new JsonResponse(null);
    }
    
    public function listFromCategoryAction($categoryId){
            $category = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->find($categoryId);

            $projects = $category->getProjects();


            $responseArray = array();

            for ($i = 0; $i < count($projects); $i++) {
                if($projects[$i]->getActive() && !$projects[$i]->getDeleted()) {
                    if ($this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {
                        $hasDonated = $this->hasDonated($projects[$i]->getId());
                    } else {
                        $hasDonated = false;
                    }
                    if ($projects[$i]->getTargetAmount() == 0) {
                        $progress = 0;
                    } else {
                        $progress = floor(($projects[$i]->getCurrentAmount() / $projects[$i]->getTargetAmount()) * 100);
                    }
                    $item = array(
                        "id" => $projects[$i]->getId(),
                        "title" => $projects[$i]->getTitle(),
                        "titlePictureUrl" => $projects[$i]->getTitlePictureUrl(),
                        "description" => $projects[$i]->getDescription(),
                        "shortinfo" => $projects[$i]->getShortinfo(),
                        "created_at" => $projects[$i]->getCreatedAt()->format('d.m.Y'),
                        "targetAmount" => $projects[$i]->getTargetAmount(),
                        "currentAmount" => $projects[$i]->getCurrentAmount(),
                        "deleted" => $projects[$i]->getDeleted(),
                        "progress" => $progress,
                        "currentDonators" => $projects[$i]->getCurrentDonators(),
                        "hasDonated" => $hasDonated
                    );
                    array_push($responseArray, $item);
                }
                $responseArray = (object)$responseArray;
        }
        return new JsonResponse($responseArray);
    }

    public function ifFollowing($projectId){
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {
            //anzahl der ProjectIds checken

            $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
            $follower = $repository->getFollowers();

            $user = $this->getUser();
            //$user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(1);

            foreach ($follower as $follower) {
                if ($follower->getUsers()->getId() == $user->getId()) {
                    return true;
                } else {
                    continue;
                }
            }
            return false;
        }
        return false;
    }

    public function listFollowingAction(){
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {
            $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->findAll();

            $responseArray = array();


            foreach ($projects as $project) {

                if ($this->ifFollowing($project) == true) {
                    if($project->getTargetAmount()==0){
                        $progress = 0;
                    } else {
                        $progress = floor(($project->getCurrentAmount() / $project->getTargetAmount()) * 100);
                    }
                    $item = array(
                        "id" => $project->getId(),
                        "title" => $project->getTitle(),
                        "titlePictureUrl" => $project->getTitlePictureUrl(),
                        "shortinfo" => $project->getShortinfo(),
                        "created_at" => $project->getCreatedAt()->format('d.m.Y'),
                        "targetAmount" => $project->getTargetAmount(),
                        "currentAmount" => $project->getCurrentAmount(),
                        "deleted" => $project->getDeleted(),
                        "progress" => $progress,
                        "currentDonators" => $project->getCurrentDonators(),
                        "hasDonated" => $this->hasDonated($project->getId()),
                    );
                    array_push($responseArray, $item);
                }
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
        }
        return new JsonResponse(null);
    }

    public function hasDonated($projectId){
        //anzahl der ProjectIds checken

        if ( $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {

            $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
            $donations = $repository->getDonations();

            $user = $this->getUser();
            //$user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(1);

            foreach($donations as $donation){
                if($donation->getUsers()->getId() == $user->getId()){
                    return true;
                }
            }

            return false;

        }

        return null;

    }

    public function listDonatedAction(){
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {
            $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->findAll();

            $responseArray = array();

            foreach ($projects as $project) {

                if ($this->hasDonated($project) == true) {
                    if($project->getDeleted()){
                        $deleted = true;
                    }else{
                        $deleted = false;
                    }
                    if($project->getTargetAmount()==0){
                        $progress = 0;
                    } else {
                        $progress = floor(($project->getCurrentAmount() / $project->getTargetAmount()) * 100);
                    }
                    $item = array(
                        "id" => $project->getId(),
                        "title" => $project->getTitle(),
                        "titlePictureUrl" => $project->getTitlePictureUrl(),
                        "shortinfo" => $project->getShortinfo(),
                        "created_at" => $project->getCreatedAt()->format('d.m.Y'),
                        "targetAmount" => $project->getTargetAmount(),
                        "currentAmount" => $project->getCurrentAmount(),
                        "deleted" => $project->getDeleted(),
                        "progress" => $progress,
                        "currentDonators" => $project->getCurrentDonators(),
                        "hasDonated" => $this->hasDonated($project->getId()),
                        "isDeleted" => $deleted,
                    );
                    array_push($responseArray, $item);
                }
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
        }
        return new JsonResponse(null);
    }

    public function showAction($projectId){
            $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

            $responseArray = array();
            if ($this->hasDonated($projectId)){
                $description = $projects->getDescriptionPrivate();
            } else {
                $description = $projects->getDescription();
            }
            $progress = floor(($projects->getCurrentAmount() / $projects->getTargetAmount()) * 100);

            $hasDonated = $this->hasDonated($projects->getId());

            $item = array(
                "id" => $projects->getId(),
                "title" => $projects->getTitle(),
                "active" => $projects->getActive(),
                "titlePictureUrl" => $projects->getTitlePictureUrl(),
                "description" => $description,
                "shortInfo" => $projects->getShortinfo(),
                "created_at" => $projects->getCreatedAt()->format('d.m.Y'),
                "created_at_backend" => $projects->getCreatedAt()->format('Y-m-d'),
                "targetAmount" => $projects->getTargetAmount(),
                "currentAmount" => $projects->getCurrentAmount(),
                "deleted" => $projects->getDeleted(),
                "progress" => $progress,
                "currentDonators" => $projects->getCurrentDonators(),
                "category" => $projects->getCategory()->getCategoryText(),
                "hasDonated"=>$hasDonated

            );
            array_push($responseArray, $item);

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
    }

    
    public function showActiveProjectAction(){
        $projects = $this->getUser()->getProjects();

        foreach($projects as $project){
            if($project->getActive()){
                
                $projectId = $project->getId();

                $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

                $responseArray = array();
                if ($this->hasDonated($projectId)){
                    $description = $projects->getDescriptionPrivate();
                } else {
                    $description = $projects->getDescription();
                }
                $progress = floor(($projects->getCurrentAmount() / $projects->getTargetAmount()) * 100);

                $hasDonated = $this->hasDonated($projects->getId());

                $item = array(
                    "id" => $projects->getId(),
                    "title" => $projects->getTitle(),
                    "active" => $projects->getActive(),
                    "titlePictureUrl" => $projects->getTitlePictureUrl(),
                    "description" => $description,
                    "shortInfo" => $projects->getShortinfo(),
                    "created_at" => $projects->getCreatedAt()->format('d.m.Y'),
                    "created_at_backend" => $projects->getCreatedAt()->format('Y-m-d'),
                    "targetAmount" => $projects->getTargetAmount(),
                    "currentAmount" => $projects->getCurrentAmount(),
                    "deleted" => $projects->getDeleted(),
                    "progress" => $progress,
                    "currentDonators" => $projects->getCurrentDonators(),
                    "category" => $projects->getCategory()->getCategoryText(),
                    "hasDonated"=>$hasDonated

                );
                array_push($responseArray, $item);

                $responseArray = (object)$responseArray;

                return new JsonResponse($responseArray);

            }
        }
        return new JsonResponse('no active project');
        
    }

    public function hasActive(){
        
        $projects = $this->getUser()->getProjects();
        //$projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(3)->getProjects();

        foreach($projects as $project){
            if($project->getActive()==true){
                return true;
            }else{
                continue;
            }
        }

        return false;
        
    }

    /**
     * @param Request $request
     * @return bool|JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {
            if($this->hasActive() == false){

                $form = $this->createFormBuilder()
                    ->add('titlePictureUrl')
                    ->add('title')
                    ->add('shortInfo')
                    ->add('description')
                    ->add('descriptionPrivate')
                    ->add('uploadFile')
                    ->add('targetAmount')
                    ->add('category')
                    ->add('file')
                    ->getForm();

                if ($request->isMethod('POST')) {

                    $form->submit($request->request->all($form->getName()));

                    if ($form->isSubmitted()) {

                        $date = new \DateTime('now');

                        // data is an array with "phone" and "period" keys
                        $data = $form->getData();

                        $em = $this->getDoctrine()->getManager('default');
                        $pictureEm = $this->getDoctrine()->getManager();

                        $project = new Project;
                        $project->setTitle($data["title"]);
                        $project->setShortinfo($data["shortInfo"]);
                        $project->setDescription($data["description"]);
                        $project->setDescriptionPrivate($data["descriptionPrivate"]);
                        $project->setTitlePictureUrl($data["titlePictureUrl"]);

                        $project->setActive(true);
                        $project->setCreatedAt($date);
                        $project->setTargetAmount($data["targetAmount"]);
                        $project->setCurrentAmount(0);
                        $project->setCurrentDonators(0);

                        $project->setCategory($this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->findOneBy(
                            array('categoryText' => $data["category"])
                        ));

                        $project->setUsers($this->getUser());
                        //$project->setUsers($this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(3));

                        //var_dump(count($_FILES['file']["name"]));
                        //return new JsonResponse("Test");

                        $em->persist($project); // I set/modify the properties then persist

                        //$project->upload();

                        //var_dump($_FILES);

                        //file upload
                        $rand = rand(1,30000);
                        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/bundles/htlspendenportal/img/';
                        $filename = trim(addslashes($_FILES['titlePictureUrl']['name']));
                        $filename = $rand.preg_replace('/\s+/', '_', $filename);
                        $target_file = $target_dir . $filename;
                        $project->setTitlePictureUrl($filename);
                        $uploadOk = 1;
                        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                        // Check if image file is a actual image or fake image
                        if (isset($_POST["submit"])) {
                            $check = getimagesize($_FILES["titlePictureUrl"]["tmp_name"]);
                            if ($check !== false) {
                                $uploadOk = 1;
                            } else {
                                $uploadOk = 0;
                                return new JsonResponse("File is not an image.");
                            }
                        }
                        // Check file size
                        if ($_FILES["titlePictureUrl"]["size"] > 6000000) {
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
                            if (move_uploaded_file($_FILES["titlePictureUrl"]["tmp_name"], $target_file)) {
                            } else {
                                return new JsonResponse("Sorry, there was an error uploading your file.");
                            }
                        }

                        $em->flush();
                        if($_FILES['file']['name'][0] != null){
                            for($i = 0; $i<count($_FILES['file']["name"]);$i++){
                                //var_dump($_FILES['file']["name"][$i]);
                                //return new JsonResponse('Test');
                                $picture = new Picture();
                                
                                $picture->setCreatedAt($date);

                                $picture->setProjects($this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($project->getId()));

                                $rand = rand(1,30000);
                                $filename = trim(addslashes($_FILES['file']['name'][$i]));
                                $filename = $rand.preg_replace('/\s+/', '_', $filename);
                                $target_file = $target_dir . $filename;
                                $uploadOk = 1;
                                $picture->setPictureUrl($filename);
                                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                                if (isset($_POST["submit"])) {
                                    $check = getimagesize($_FILES["file"]["tmp_name"][$i]);
                                    if ($check !== false) {
                                        $uploadOk = 1;
                                    } else {
                                        $uploadOk = 0;
                                        return new JsonResponse("File is not an image.");
                                    }
                                }
                                // Check file size
                                if ($_FILES["file"]["size"][$i] > 6000000) {
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
                                    if(move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file)){
                                        $uploadOk = 1;
                                    }
                                }
                                $pictureEm->persist($picture);
                                $pictureEm->flush();
                                $pictureEm->clear();
                            }
                        }
                        return $this->redirectToRoute('htl_spendenportal_empfaenger_dashboard');
                    }
                    return false;
                }
                return new JsonResponse("false method");
            }
            return new JsonResponse("has active so you cannot create another");
        }
        return new JsonResponse("not logged in");
    }

    public function updateAction(Request $request)
    {
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {    // && $this->getUser()->getProjects()->find($projectId)

            $form = $this->createFormBuilder()
                ->add('title')
                ->add('shortInfo')
                ->add('description')
                ->add('descriptionPrivate')
                ->add('titlePictureUrl')
                ->add('targetAmount')
                ->add('category')
                ->getForm();

            $projects = $this->getUser()->getProjects();

            foreach ($projects as $project) {
                if ($project->getActive()) {
                    $projectId = $project->getId();
                    if ($request->isMethod('POST')) {

                        $form->submit($request->request->all($form->getName()));
                        //return new JsonResponse($request);

                        if ($form->isSubmitted()) {

                            $date = new \DateTime('now');

                            // data is an array with "phone" and "period" keys
                            $data = $form->getData();

                            $em = $this->getDoctrine()->getManager();

                            //return new JsonResponse(date_parse_from_format('Y-m-d', $data["created_at"]));

                            $project = $em->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
                            $project->setTitle($data["title"]);
                            $project->setShortinfo($data["shortInfo"]);
                            $project->setDescription($data["description"]);
                            $project->setDescriptionPrivate($data["descriptionPrivate"]);

                            if(!$_FILES['titlePictureUrl']['name']==""){
                                //file upload
                                $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/bundles/htlspendenportal/img/';
                                $target_file = $target_dir . basename($_FILES["titlePictureUrl"]["name"]);
                                $uploadOk = 1;

                                if (unlink($target_dir . $project->getTitlePictureUrl())) {
                                    $uploadOk = 1;
                                }
                                $project->setTitlePictureUrl($_FILES['titlePictureUrl']['name']);

                                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                                // Check if image file is a actual image or fake image
                                if (isset($_POST["submit"])) {
                                    $check = getimagesize($_FILES["titlePictureUrl"]["tmp_name"]);
                                    if ($check !== false) {
                                        $uploadOk = 1;
                                    } else {
                                        $uploadOk = 0;
                                        return new JsonResponse("File is not an image.");
                                    }
                                }
                                // Check file size
                                if ($_FILES["titlePictureUrl"]["size"] > 6000000) {
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
                                    if (move_uploaded_file($_FILES["titlePictureUrl"]["tmp_name"], $target_file)) {
                                    } else {
                                        return new JsonResponse("Sorry, there was an error uploading your file.");
                                    }
                                }
                            }

                            //$project->setUsers($this->getUser());
                            //$project->setUsers($this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(3));

                            $em->flush();

                            return $this->redirectToRoute('htl_spendenportal_empfaenger_dashboard');
                        }

                        return false;
                    }
                    return new JsonResponse("false method");
                }
            }
            return new JsonResponse('no active project');
        }
        return new JsonResponse("not logged in");
    }

    public function deleteAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER') && $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $projects = $this->getUser()->getProjects();
            //$projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(4)->getProjects();

            foreach($projects as $project) {
                if ($project->getActive()) {
                    $projectId = $project->getId();
                }
            }

            $em = $this->getDoctrine()->getManager();
            $project = $em->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

            if (!$project) {
                throw $this->createNotFoundException(
                    'No Project found for id ' . $projectId
                );
            }

            $project->setDeleted(true);

            $em->flush();


            return $this->redirectToRoute('htl_spendenportal_empfaenger_dashboard');
            
            //$em->remove($project);

        }
        return new JsonResponse(false);
    }
}
