<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Observation;
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

        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');

        $observations = $this->container->get('app.geoloc')->getNearest($latitude,$longitude,$this->getParameter('gps_distance'));
        foreach ($observations as $observation){
            $response[] = [
                'id' => $observation->getId(),
                'validated' => $observation->getValidated(),
                'watched' => $observation->getWatched(),
                'place' => $observation->getPlace(),
                'latitude' => $observation->getLatitude(),
                'longitude' => $observation->getLongitude(),
                'imagePath' => $observation->getImagePath(),
                'comments' => $observation->getComments(),
                'individuals' => $observation->getIndividuals(),
                'observer' => $observation->getUser()->getName(),
                'naturalist' => $observation->getNaturalist()->getName(),
                'regnum' => $observation->getTaxref()->getRegnum(),
                'phylum' => $observation->getTaxref()->getPhylum(),
                'classis' => $observation->getTaxref()->getClassis(),
                'ordo' => $observation->getTaxref()->getOrdo(),
                'familia' => $observation->getTaxref()->getFamilia(),
                'validName' => $observation->getTaxref()->getValidName(),
                'commonName' => $observation->getTaxref()->getCommonName()
            ];
        }
        $viewHandler = $this->get('fos_rest.view_handler');
        $view = View::create($response);
        $view->setFormat('json');
        return $viewHandler->handle($view);
    }

    /**
     * Get observations with some filters to show on map
     *
     * @Route("/search", name="obs.search")
     * @Method({"POST"})
     * @param Request $request
     *  @return \Symfony\Component\HttpFoundation\Response
     */
    public function getObservationsForMapAction(Request $request)
    {
        // Get filters
        $specimen   = trim($request->get('bird'));
        $department = (int) $request->get('department');
        $result         = array();

        $em = $this->getDoctrine()->getManager();
        $observations = $em->getRepository('AppBundle:Observation')->getObservationsWithFilter($specimen, $department);

        foreach ($observations as $observation){
            $result[] = array(
                'place'     => $observation->getPlace(),
                'latitude'  => $observation->getLatitude(),
                'longitude' => $observation->getLongitude()
            );
        }

        $html = $this->render('observation/list.html.twig', [
            'obslist' => $observations,
        ])->getContent();
        $result['html'] = $html;

        return new JsonResponse($result);
    }



}
