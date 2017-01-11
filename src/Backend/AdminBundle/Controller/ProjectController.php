<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    /**
     * @Route("/project", name="projectslist")
     *
     * listing the Projects
     */
    public function indexAction()
    {

        $projects = $this->getDoctrine()
            ->getRepository('BackendAdminBundle:Projectamount')->findAll();

        /*
        $projectamounts = $this->getDoctrine()
            ->getRepository('AppBundle:projectamount')
            ->findOneByIdJoinedToCategory($id);

        $projectamount = $projectamounts->getProjects();
        */
        if (!$projects) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }

        return $this->render('BackendAdminBundle::listProjects.html.twig', array(
            'projects' => $projects,
        ));
    }

    /**
     * @Route("/project/1", name="projectpage")
     */
    public function showAction()
    {
        return $this->render('BackendAdminBundle::editProject.html.twig');
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
