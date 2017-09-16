<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('admin\index.html.twig',[
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'cards' => $this->container->get('app.admin')->getHomeContent(),
            'breadcrumb' => $this->container->get('app.admin')->getHomeBreadcrumb()
        ]);
    }
}
