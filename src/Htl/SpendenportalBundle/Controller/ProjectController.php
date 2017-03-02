<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Htl\SpendenportalBundle\Entity\Project;

class ProjectController extends Controller
{
    public function listAction(){
            $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->findAll();

            $responseArray = array();

            $hasDonated = null;

            foreach ($projects as $project) {
                //if($project->getActive()) {
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
                        "progress" => $progress,
                        "currentDonators" => $project->getCurrentDonators(),
                        "hasDonated" => $hasDonated
                    );
                    array_push($responseArray, $item);
                //}
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
    }

    public function listMyActiveAction(){
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $projects = $this->getUser()->getProjects();

            //$projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(3)->getProjects();
            
            $responseArray = array();
            
            foreach ($projects as $project) {
                if ($project->getActive()) {
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
                if ($this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {
                    $hasDonated = $this->hasDonated($projects[$i]->getId());
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
                    "progress" => $progress,
                    "currentDonators" => $projects[$i]->getCurrentDonators(),
                    "hasDonated" => $hasDonated
                );
                array_push($responseArray, $item);
            }

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
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

    public function ifFollowing($projectId){
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_DONATOR')) {
            //anzahl der ProjectIds checken

            $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
            $follower = $repository->getFollowers();

            $user = $this->getUser();
            $user = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(1);

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
                "progress" => $progress,
                "currentDonators" => $projects->getCurrentDonators(),
                "category" => $projects->getCategory()->getCategoryText(),
                "hasDonated"=>$hasDonated

            );
            array_push($responseArray, $item);

            $responseArray = (object)$responseArray;

            return new JsonResponse($responseArray);
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

    public function createAction(Request $request)
    {
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $form = $this->createFormBuilder()
                ->add('title')
                ->add('category')
                ->add('created_at')
                ->add('targetAmount')
                ->add('shortInfo')
                ->add('currentAmount')
                ->add('description')
                ->add('descriptionPrivate')
                ->getForm();

            if ($request->isMethod('POST')) {

                $form->submit($request->request->all($form->getName()));

                if ($form->isSubmitted()) {

                    // data is an array with "phone" and "period" keys
                    $data = $form->getData();

                    $em = $this->getDoctrine()->getManager();

                    //return new JsonResponse(date_parse_from_format('Y-m-d', $data["created_at"]));

                    $project = new Project;
                    $project->setTitle($data["title"]);
                    $project->setShortinfo($data["shortInfo"]);
                    $project->setDescription($data["description"]);
                    $project->setDescriptionPrivate($data["descriptionPrivate"]);
                    $project->setTitlePictureUrl($data["titlePictureUrl"]);

                    $picture = new Picture();
                    $picture->setPictureUrl($data['pictureUrl']);
                    $picture->setCreatedAt(date_create_from_format('Y-m-d', $data["created_at"]));
                    $picture->setProjects($project->getId());

                    $project->setActive(true);
                    $project->setCreatedAt(date_create_from_format('Y-m-d', $data["created_at"]));
                    $project->setTargetAmount($data["targetAmount"]);
                    //$project->setCategory($data["category"]);
                    $product = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->findOneBy(
                        array('category_text' => $data["category"])
                    );

                    $project->setUsers($this->getUser());

                    // or this could be $contract = new Contract("John Doe", $data["phone"], $data["period"]);

                    $em->persist($project); // I set/modify the properties then persist

                    $em->flush();

                    return $this->render('BackendAdminBundle::listProjects.html.twig');
                }

                return $this->render('BackendAdminBundle::createProject.html.twig');
            }
            return new JsonResponse("false method");
        }
        return new JsonResponse("not logged in");
    }

    public function updateAction($projectId, $title, $desciption, $desciptionPrivate, $shortinfo, $categoryId, $user, $targetAmount, $currentAmount, $titlePictureUrl, $active){

        if ( $this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $em = $this->getDoctrine()->getManager();
            $project = $em->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

            if (!$project) {
                throw $this->createNotFoundException(
                    'No category found for id ' . $projectId
                );
            }

            $project->setTitle($title);
            $project->setTitlePictureUrl($titlePictureUrl);
            $project->setDescription($desciption);
            $project->setDescriptionPrivate($desciptionPrivate);
            $project->setShortinfo($shortinfo);
            $project->setTargetAmount($targetAmount);
            $project->setCurrentAmount($currentAmount);
            $project->setCategory($categoryId);
            $project->setUser($user);
            $project->setActive($active);

            $em->flush();

            return new JsonResponse('Updated post successful');
        }
        return new JsonResponse(false);
    }

    public function deleteAction($projectId)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER') && $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $em = $this->getDoctrine()->getManager();
            $project = $em->getRepository('HtlSpendenportalBundle:Category')->find($projectId);

            if (!$project) {
                throw $this->createNotFoundException(
                    'No category found for id ' . $project
                );
            }

            /* Schauen ob es für dieses Project childs existieren! */


            $em->remove($project);
            $em->flush();


            return new Response('Picture has been deleted!');
        }
        return new JsonResponse(false);
    }
}
