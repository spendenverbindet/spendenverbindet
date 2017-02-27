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
        $form = $this->createFormBuilder()
            ->add('title')
            ->add('category')
            ->add('created_at')
            ->add('targetAmount')
            ->add('shortInfo')
            ->add('currentAmount')
            ->add('description')
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
                //$project->setCategory($data["category"]);
                $project->setCreatedAt(date_create_from_format('Y-m-d', $data["created_at"]));
                $project->setTargetAmount($data["targetAmount"]);
                $project->setShortinfo($data["shortInfo"]);
                $project->setCurrentAmount($data["currentAmount"]);
                $project->setDescription($data["description"]);

                // or this could be $contract = new Contract("John Doe", $data["phone"], $data["period"]);

                $em->persist($project); // I set/modify the properties then persist

                $em->flush();

                return $this->render('BackendAdminBundle::listProjects.html.twig');
            }

            return $this->render('BackendAdminBundle::createProject.html.twig');
        }
    }

    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('title')
            ->add('category')
            ->add('created_at')
            ->add('targetAmount')
            ->add('shortinfo')
            ->add('currentAmount')
            ->add('description')
        ;
    }
/*
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
*/
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
