<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\UserProfilType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\Type\AccountType;
use AppBundle\Entity\User;

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
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('dashboard/profil.html.twig', array(
            'obs_validate'      => sizeof($this->container->get('app.obs')->getObservationsValidate($user))
        ));
    }

    /**
     * @Route("/profil/edit", name="dashboard.profil.edit")
     * @Method({"GET","POST"})
     */
    public function profilEditAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(UserProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->container->get('app.user')->updateProfil($user, $request->files->get('user_profil'));
            return $this->redirectToRoute('dashboard.profil');
        }
        return $this->render('dashboard/edit.html.twig', array(
            'obs_validate'              => sizeof($this->container->get('app.obs')->getObservationsValidate($user)),
            'form'                      => $form->createView(),
        ));
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
     * @Route("/profil/utilisateur/{id}/{name}", name="dashboard.user", requirements={"id": "\d+"})
     * @ParamConverter("user", options={"mapping": {"id": "id"}})
     * @Method({"GET"})
     */
    public function profilUserAction(User $user)
    {
        $me = $this->get('security.token_storage')->getToken()->getUser();

        if($user->getPrivate()) {
            $access = ($me->getId() == $user->getId() || $me->getRole() == 'ROLE_ADMIN') ? true : false;
        } else {
            $access = true;
        }

        return $this->render('dashboard/profil_read_only.html.twig', array(
            'obs_validate'              => sizeof($this->container->get('app.obs')->getObservationsValidate($user)),
            'user'                      => $user,
            'access_to_profil'          => $access
        ));
    }
}

