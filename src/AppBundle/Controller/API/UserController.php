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

        //TODO: Check for password


        $response = [];
        if ($user) {
            $response = [
                'name' => $user[0]->getName(),
                'email' => $user[0]->getEmail(),
                'role' => $user[0]->getRole(),
                'aboutme' => $user[0]->getAboutme(),
                'image' => $baseurl = $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/images/users/'.$user[0]->getImagePath(),
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
}
/*
        "id": 1080,
        "name": "Bobby",
        "email": "admin@test.com",
        "role": "ROLE_ADMIN",
        "newsletter": false,
        "token": null,
        "aboutme": null,
        "imagePath": "bobby.jpg",
        "inactive": false,
        "roleString": "Administrateur",
        "plainPassword": null,
        "created": "2017-09-28T19:26:44+02:00"

        */