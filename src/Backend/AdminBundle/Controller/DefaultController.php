<?php

namespace Backend\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

        $persons = $this->getDoctrine()
            ->getRepository('BackendAdminBundle:Person')->findAll();

        
        var_dump($persons);

        if (!$persons) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }
        

        return $this->render('BackendAdminBundle::index.html.twig', array(
            'persons' => $persons,
        ));
    }

    /**
     * @Route("/edit", name="editpage")
     */
    public function editAction()
    {
        return $this->render('BackendAdminBundle::editUser.html.twig');
    }

    
}
