<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    /**
     * Dashboard form admin user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('admin\index.html.twig',[
            'header' => [
                'bodyClass' => 'background-2',
                'breadcrumb' => $this->container->get('app.admin')->getHomeBreadcrumb()
            ]
        ]);
    }
}
