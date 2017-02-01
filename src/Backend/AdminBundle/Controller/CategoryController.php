<?php

namespace Backend\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    public function indexAction()
    {
        return $this->render('BackendAdminBundle::category.html.twig');
    }
}
