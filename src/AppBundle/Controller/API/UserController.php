<?php

namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class DefaultController
 *
 * @Route("/API/user")
 *
 * @package AppBundle\Controller
 */
class UserController extends Controller
{


    /**
     * Get user info
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userinfoAction(Request $request)
    {
        $url = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $response = $this->container->get('app.user')->getUserInfo($url);

        $viewHandler = $this->get('fos_rest.view_handler');
        $view = View::create($response);
        $view->setFormat('json');
        return $viewHandler->handle($view);
    }


    /**
     * Search user (ajax)
     *
     * @Route("/search", name="api_user_search")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $search = $request->request->get('search');
        $role = $request->request->get('role');
        $autocomplete = $em->getRepository('AppBundle:User')->autocompleteUsersByRole($role, $search);
        return new JsonResponse($autocomplete);

    }


    /**
     * Paginate user list (ajax)
     *
     * @Route("/paginate", name="api_user_paginate")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function paginateAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->searchUsersByRole(
            $request->request->get('page'),
            $request->request->get('role'),
            $this->getParameter('list_limit'),
            $request->request->get('user_id')
        );

        $html = $this->render(':admin/user:list.html.twig', [
            'users' => $users
        ])->getContent();

        return new JsonResponse(['html' => $html]);
    }

}

