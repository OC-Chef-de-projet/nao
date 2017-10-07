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
    public function aboutAction()
    {
        return $this->render('about.html.twig');
    }

    /**
     * @Route("/restez-connecte", name="stay.connected")
     * @Method({"GET"})
     */
    public function stayConnectAction()
    {
        return $this->render('stay-connected.html.twig');
    }

    /**
     * @Route("/mentions-legales", name="legacy")
     * @Method({"GET"})
     */
    public function legacyAction()
    {
        return $this->render('legacy.html.twig');
    }

    /**
     * @Route("/faq", name="faq")
     * @Method({"GET"})
     */
    public function faqAction()
    {
        return $this->render('faq.html.twig');
    }
}
