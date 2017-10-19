<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\Type\AccountType;

/**
 * Class DashboardController
 * @package AppBundle\Controller
 * @Route("/dashboard")
 * @Security("is_granted('ROLE_OBSERVER')")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/compte", name="dashboard.account")
     * @Method({"GET","POST"})
     */
    public function accountAction(Request $request)
    {
        $user           = $this->get('security.token_storage')->getToken()->getUser();
        $newsSubscribe = $this->container->get('app.news')->isSubscribe($user->getEmail());

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $wantNewsletter =  isset($request->get('account')['newsletter']) ? true : false;
            $this->container->get('app.user')->updateAccount($user, $wantNewsletter);
            return $this->redirectToRoute('dashboard.account');
        }

        return $this->render('dashboard/account.html.twig', array(
            'form'                      => $form->createView(),
            'subscribe_newsletter'    => $newsSubscribe
        ));
    }

    /**
     * @Route("/compte/delete", name="dashboard.account.delete")
     * @Method({"GET"})
     */
    public function accountDeleteAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $this->container->get('app.news')->unsubscribe($user->getEmail());
        $this->container->get('app.notification')->deleteNotificationsForUser($user);
        $this->container->get('app.cmt')->deleteCommentsForUser($user);
        $this->container->get('app.obs')->deleteObservationsForUser($user);
        $this->container->get('app.user')->deleteAccount($user);
        $this->container->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/profil", name="dashboard.profil")
     * @Method({"GET"})
     */
    public function profilAction()
    {
        return $this->render('dashboard/profil.html.twig');
    }

    /**
     * @Route("/notification", name="dashboard.notification")
     * @Method({"GET"})
     */
    public function notificationAction()
    {
        $this->container->get('app.notification')->setAsReadAll();
        return $this->render('dashboard/notification.html.twig', array(
            'notifications'     => $this->container->get('app.notification')->getLastNotifications(10)
        ));
    }

    /**
     * @Route("/profil/utilisateur/{id}/{name}", name="dashboard.user")
     * @Method({"GET"})
     */
    public function profilUserAction()
    {
        return $this->render('dashboard/profil.html.twig');
    }
}

