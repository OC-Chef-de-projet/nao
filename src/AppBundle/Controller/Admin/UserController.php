<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Form\Type\UserType;
use AppBundle\Form\Type\UserEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{

    /**
     * Lists all user entities.
     *
     */
    public function indexAction($page = 1, $role = 'ROLE_OBSERVER')
    {
        $em = $this->getDoctrine()->getManager();

        $limit = 8;
        $users = $em->getRepository('AppBundle:User')->getUsersByRole($page,$role,$limit);
        $totalUsers = $users->count();
        $totalDisplayed = $users->getIterator()->count();
        $maxPages = ceil($users->count() / $limit);
        return $this->render('@AdminUser/index.html.twig', array(
            'tabs' => $this->container->get('app.user')->getUsersTabs($role,$page),
            'users' => $users->getIterator(),
            'totalUsers' => $totalUsers,
            'totalDisplayed' => $totalDisplayed,
            'current' => $page,
            'maxPages' => $maxPages,
            'breadcrumb' => $this->container->get('app.user')->getIndexBreadcrumb()
        ));
    }


}
