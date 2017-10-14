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
        // Only for user not logged
        if($this->getUser()){
            return $this->redirectToRoute('homepage');
        }

        $helper = $this->get('security.authentication_utils');
        $form= $this->createForm(LoginType::class, ['_email' => $helper->getLastUsername()]);

        return $this->render('security/login.html.twig', [
            'form'      => $form->createView(),
            'error'     => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/compte/recuperation", name="password_lost")
     * @Method({"GET", "POST"})
     */
    public function passwordLostAction(Request $request)
    {
        // Only for user not logged
        if($this->getUser()){
            return $this->redirectToRoute('homepage');
        }

        if ($request->isMethod('POST')) {
            $email = $request->get('email');
            // CHECK IF EMAIL IS VALID AND SEND MAIL FOR RAZ PASSWORD
            // WHEN FINISH
        }

        return $this->render('security/password_recovery.html.twig');
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
