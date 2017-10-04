<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class RegistrationController extends Controller
{
    /**
     * @Route("/inscription", name="registration")
     * @Method({"GET","POST"})
     */
    public function registrationAction(Request $request)
    {
        return $this->render('registration.html.twig');
    }
}