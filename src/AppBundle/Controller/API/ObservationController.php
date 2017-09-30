<?php

namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\View\View;

/**
 * Class ObservationController
 * @package AppBundle\Controller\API
 */
class ObservationController extends Controller
{


    /**
     * Observation list
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function obslistAction(Request $request)
    {

        $url = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $response = $this->container->get('app.obs')->getlist($url);

        $viewHandler = $this->get('fos_rest.view_handler');
        $view = View::create($response);
        $view->setFormat('json');
        return $viewHandler->handle($view);
    }

    /**
     * Add an observation
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function obsaddAction(Request $request)
    {
        $response = $this->container->get('app.obs')->add($request->get('observation'));

        $viewHandler = $this->get('fos_rest.view_handler');
        $view = View::create($response);
        $view->setFormat('json');
        return $viewHandler->handle($view);
    }

}

