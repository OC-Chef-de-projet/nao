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

        // request->get('email'),
        // request->get('key')

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findByEmail($request->get('email'));

        // Check for password


        $response = [];
        if ($user) {
            $response = [
                'name' => $user[0]->getName(),
                'email' => $user[0]->getEmail(),
                'role' => $user[0]->getRole(),
                'aboutme' => $user[0]->getAboutme(),
                'image' => $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/images/users/' . $user[0]->getImagePath(),
                'created' => $user[0]->getCreated(),
                'roleString' => $user[0]->getRoleString(),
                'name' => $user[0]->getName(),
                'name' => $user[0]->getName(),
            ];
        }

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

            //$logger->info('I just got the logger '.$page);
            $users = $em->getRepository('AppBundle:User')->searchUsersByRole($page, $role, $this->getParameter('list_limit'), $user_id);

            $html = $this->render(':admin/user:list.html.twig', [
                'users' => $users
            ])->getContent();

            return new JsonResponse(['html' => $html]);
    }

}

