<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;
use Htl\SpendenportalBundle\Entity\Project;
use Symfony\Component\Validator\Constraints\DateTime;

class ProjectController extends Controller
{

    public function indexAction()
    {

        $projects = $this->getDoctrine()
            ->getRepository('HtlSpendenportalBundle:Project')->findAll();

        if (!$projects) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }

        return $this->render('BackendAdminBundle::listProjects.html.twig');
    }
    
    public function renderEditAction()
    {
        return $this->render('BackendAdminBundle::editProject.html.twig');
    }

    public function listAction()
    {
        $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->findAll();

        $responseArray = array();

        for($i=0;$i<count($projects);$i++){
            $progress = floor(($projects[$i]->getCurrentAmount()/$projects[$i]->getTargetAmount())*100);
            $item = array(
                "id"=>$projects[$i]->getId(),
                "title"=>$projects[$i]->getTitle(),
                "titlePictureUrl"=>$projects[$i]->getTitlePictureUrl(),
                "description"=>$projects[$i]->getDescription(),
                "shortinfo"=>$projects[$i]->getShortinfo(),
                "created_at"=>$projects[$i]->getCreatedAt()->format('d.m.Y'),
                "targetAmount"=>$projects[$i]->getTargetAmount(),
                "currentAmount"=>$projects[$i]->getCurrentAmount(),
                "progress"=>$progress,
                "currentDonators"=>$projects[$i]->getCurrentDonators()
            );
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }

    public function showAction($projectId)
    {
        $projects = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

        $responseArray = array();
        
        $progress = floor(($projects->getCurrentAmount()/$projects->getTargetAmount())*100);
        $item = array(
            "id"=>$projects->getId(),
            "title"=>$projects->getTitle(),
            "active"=>$projects->getActive(),
            "titlePictureUrl"=>$projects->getTitlePictureUrl(),
            "description"=>$projects->getDescription(),
            "descriptionPrivate"=>$projects->getDescriptionPrivate(),
            "shortInfo"=>$projects->getShortinfo(),
            "created_at"=>$projects->getCreatedAt()->format('Y-m-d'),
            "targetAmount"=>$projects->getTargetAmount(),
            "currentAmount"=>$projects->getCurrentAmount(),
            "progress"=>$progress,
            "category"=>$projects->getCategory()->getCategoryText()
        );
        array_push($responseArray, $item);

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }

    public function createAction(Request $request)
    {
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $form = $this->createFormBuilder()
                ->add('titlePictureUrl')
                ->add('title')
                ->add('shortInfo')
                ->add('description')
                ->add('descriptionPrivate')
                ->add('pictureUrl')
                ->add('targetAmount')
                ->add('category')
                ->getForm();

            if ($request->isMethod('POST')) {

                $form->submit($request->request->all($form->getName()));

                if ($form->isSubmitted()) {

                    $date = new \DateTime('now');

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
                    $picture->setCreatedAt($date); //date_create_from_format('Y-m-d', $data["created_at"])
                    $picture->setProjects($project->getId());

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

                    // or this could be $contract = new Contract("John Doe", $data["phone"], $data["period"]);

                    $em->persist($project); // I set/modify the properties then persist

                    $em->flush();

                    return new JsonResponse($project);
                    return $this->render('BackendAdminBundle::listProjects.html.twig');
                }

                return $this->render('BackendAdminBundle::createProject.html.twig');
            }
            return new JsonResponse("false method");
        }
        return new JsonResponse("not logged in");
    }

    public function updateAction($projectId, Request $request)
    {
        //if ( $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $form = $this->createFormBuilder()
                ->add('title')
                ->add('shortInfo')
                ->add('description')
                ->add('descriptionPrivate')
                ->add('titlePictureUrl')
                //->add('pictureUrl')
                ->add('category')
                ->getForm();

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
                    $project->setTitlePictureUrl($data["titlePictureUrl"]);

                    // $picture = new Picture();

                    //$picture->setPictureUrl($data['pictureUrl']);
                    //$picture->setCreatedAt($date); //date_create_from_format('Y-m-d', $data["created_at"])
                    //$picture->setProjects($project->getId());

                    $project->setCategory($this->getDoctrine()->getRepository('HtlSpendenportalBundle:Category')->findOneBy(
                        array('categoryText' => $data["category"])
                    ));

                    $project->setUsers($this->getUser());
                    //$project->setUsers($this->getDoctrine()->getRepository('HtlSpendenportalBundle:User')->find(3));

                    $em->flush();
                    
                    return $this->render('BackendAdminBundle::listProjects.html.twig');
                }

                return $this->render('BackendAdminBundle::createProject.html.twig');
            }
            return new JsonResponse("false method");
       // }
        return new JsonResponse("not logged in");
    }

    public function renderCreateAction()
    {
        return $this->render('BackendAdminBundle::createProject.html.twig');
    }
    
/*
    public function showAction($productId)
    {
        $product = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->find($productId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$productId
            );
        }

        // ... do something, like pass the $product object into a template
    }*/
}
