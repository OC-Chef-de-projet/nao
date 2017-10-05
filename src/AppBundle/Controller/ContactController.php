<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ContactController extends Controller
{

    /**
     * @Route("/contact", name="contact")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->render('contact.html.twig');
    }
}
