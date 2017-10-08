<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
        return $this->render('dashboard/account.html.twig');
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
        return $this->render('dashboard/notification.html.twig');
    }
}

