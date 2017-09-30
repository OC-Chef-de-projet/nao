<?php

namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\View\View;

/**
 * Class DefaultController
 *
 * @package AppBundle\Controller
 */
class UserController extends Controller
{


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
     * @param Request $request
     * @return Response
     */
    public function paginateAction(Request $request)
    {


        $em = $this->getDoctrine()->getManager();

            $page = $request->request->get('page');
            $role = $request->request->get('role');
            $user_id = $request->request->get('user_id');

            $users = $em->getRepository('AppBundle:User')->searchUsersByRole($page, $role, $this->getParameter('list_limit'), $user_id);

            $html = $this->render(':admin/user:list.html.twig', [
                'users' => $users
            ])->getContent();

            return new JsonResponse(['html' => $html]);
    }

}

