<?php

namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\View\View;


/**
 * Class BlogController
 * @package AppBundle\Controller
 * @Route("/API/observation")
 */
class ObservationController extends Controller
{

    /**
     * @Route("/list", name="obs.list")
     * @Method({"POST"})
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
     * @Route("/add", name="obs.add")
     * @Method({"POST"})
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

    /**
     * Get a region from GPS
     *
     * @Route("/region", name="obs.region")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function regionAction(Request $request)
    {
        $latitude =$request->get('latitude');
        $longitude = $request->get('longitude');

        $response = $this->container->get('app.geoloc')->getFranceRegion($latitude,$longitude);

        $viewHandler = $this->get('fos_rest.view_handler');
        $view = View::create($response);
        $view->setFormat('json');
        return $viewHandler->handle($view);

    }

    /**
     * Get all observations from a GPS coordinate
     *
     * @Route("/nearest", name="obs.nearest")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function nearestAction(Request $request)
    {

        $distance = 10;
        $latitude =$request->get('latitude');
        $longitude = $request->get('longitude');

        $response = $this->container->get('app.geoloc')->getNearest($latitude,$longitude,$distance);

        $viewHandler = $this->get('fos_rest.view_handler');
        $view = View::create($response);
        $view->setFormat('json');
        return $viewHandler->handle($view);

    }



}
