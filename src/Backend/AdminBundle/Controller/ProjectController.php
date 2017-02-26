<?php

namespace Backend\AdminBundle\Controller;

use Doctrine\DBAL\Types\TextType;
use Leafo\ScssPhp\Node\Number;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
            "titlePictureUrl"=>$projects->getTitlePictureUrl(),
            "description"=>$projects->getDescription(),
            "shortinfo"=>$projects->getShortinfo(),
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
    /*
    public function createAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('title', TextType::class)
            ->add('category', TextType::class)
            ->add('created_at', DateTimeType::class)
            ->add('targetAmount', NumberType::class)
            ->add('shortinfo', TextType::class)
            ->add('currentAmount', NumberType::class)
            ->add('description', TextareaType::class)
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);

            // data is an array with "phone" and "period" keys
            $data = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository('HtlSpendenportalBundle:Project');
            $user->setTitle($data["title"]);
            $user->setCategory($data["category"]);
            $user->setCreatedAt($data["created_at"]);
            $user->setTargetAmount($data["targetAmount"]);
            $user->setShortinfo($data["shortinfo"]);
            $user->setCurrentAmount($data["currentAmount"]);
            $user->setDescription($data["description"]);

            // or this could be $contract = new Contract("John Doe", $data["phone"], $data["period"]);

            $em->persist($user); // I set/modify the properties then persist
        }
    }
*/
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('title', TextType::class)
            ->add('category', TextType::class)
            ->add('created_at', DateTimeType::class)
            ->add('targetAmount', NumberType::class)
            ->add('shortinfo', TextType::class)
            ->add('currentAmount', NumberType::class)
            ->add('description', TextareaType::class)
        ;
    }

    public function createProjectAction(Request $request)
    {
        // 1) build the form
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) save the Project!
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('BackendAdminBundle::listProjects.html.twig');
        }
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
