<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\LoginType;
use AppBundle\Form\Type\ChangePasswordType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
        $form = $this->createForm(LoginType::class, ['_email' => $helper->getLastUsername()]);

        return $this->render('security/login.html.twig', [
            'form'      => $form->createView(),
            'error'     => is_null($helper->getLastAuthenticationError()) ? false : true,
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
            $result = $this->container->get('app.user')->recoveryAccount($request->get('email'));
            if($result){
                $this->addFlash("success", true);
                return $this->redirectToRoute('password_lost');
            }
        }

        return $this->render('security/password_recovery.html.twig', array(
            'error'     => ( $request->isMethod('POST') && !$result ) ? true :false
        ));
    }

    /**
     * @Route("/compte/nouveau-mot-de-passe/{code}", name="password_reset", requirements={"code": "[a-z0-9]+"})
     * @Method({"GET", "POST"})
     * @ParamConverter("user", options={"mapping": {"code": "token"}})
     */
    public function resetPasswordAction(User $user, Request $request)
    {
        // Only for user not logged
        if($this->getUser()){
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->container->get('app.user')->updatePassword($user);
            return $this->redirectToRoute('password_reset_success');
        }

        return $this->render('security/password_reset.html.twig', array(
            'form'      => $form->createView()
        ));
    }

    /**
     * @Route("/compte/validation/nouveau-mot-de-passe", name="password_reset_success")
     * @Method({"GET"})
     */
    public function resetPasswordSuccessAction()
    {
        return $this->render('security/password_reset_success.html.twig');
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
