<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard/compte", name="dashboard.account")
     * @Method({"GET","POST"})
     */
    public function accountAction(Request $request)
    {
        return $this->render('dashboard/account.html.twig');
    }

    /**
     * @Route("/dashboard/profil", name="dashboard.profil")
     * @Method({"GET"})
     */
    public function profilAction()
    {
        return $this->render('dashboard/profil.html.twig');
    }

    /**
     * @Route("/dashboard/notification", name="dashboard.notification")
     * @Method({"GET"})
     */
    public function notificationAction()
    {
        return $this->render('dashboard/notification.html.twig');
    }
}

