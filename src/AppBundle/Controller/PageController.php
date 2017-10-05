<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class PageController extends Controller
{

    /**
     * @Route("/a-propos", name="about")
     * @Method({"GET"})
     */
    public function aboutShowAction()
    {
        return $this->render('about.html.twig');
    }

}
