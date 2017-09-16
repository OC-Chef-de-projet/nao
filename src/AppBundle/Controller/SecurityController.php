<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     *
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        $formLogin = $this->createForm(LoginType::class, ['_email' => $helper->getLastUsername()]);

        return $this->render('security/login.html.twig', [
            'form_login' => $formLogin->createView(),
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * 
     */
    public function loginCheckAction()
    {

    }
}
