<?php

namespace Gao\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GaoAdminBundle:Default:index.html.twig');
    }
}
