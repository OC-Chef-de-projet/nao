<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class SecurityController
 *
 * @package AppBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * Login to backoffice
     *
     * @return \Symfony\Component\HttpFoundation\Response
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
     * Check login
     */
    public function loginCheckAction()
    {

    }
}
