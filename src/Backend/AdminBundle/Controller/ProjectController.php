<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
