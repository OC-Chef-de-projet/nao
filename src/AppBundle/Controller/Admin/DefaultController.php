<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * Class DefaultController
 *
 * @Route("/admin")
 *
 * @package AppBundle\Controller\Admin
 */
class DefaultController extends Controller
{

    /**
     * Dashboard form admin user
     *
     * @Route("/", name="admin_homepage")
     * @Method({"GET"})

     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {

        return $this->render('admin\index.html.twig');
    }
}
