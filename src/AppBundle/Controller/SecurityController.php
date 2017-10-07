<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\LoginType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class SecurityController
 *
 * @package AppBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @Route("/connexion", name="login")
     * @Method({"GET","POST"})
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
     * @Route("/logout", name="logout")
     * @Method({"GET"})
     */
    public function logoutAction()
    {

    }

    /**
     * Check login
     */
    public function loginCheckAction()
    {

    }
}
