<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('admin\index.html.twig',[
            'bodyClass' => 'background-2',
            'breadcrumb' => $this->container->get('app.admin')->getHomeBreadcrumb()
        ]);
    }
}
