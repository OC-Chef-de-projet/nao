<?php

namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Class BlogController
 * @package AppBundle\Controller
 * @Route("/API/region")
 */
class GeolocController extends Controller
{

    /**
     * Get list of searching city
     *
     * @Route("/search/city", name="region.search.city")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchCityOfRegionAction(Request $request)
    {
        $em             = $this->getDoctrine()->getManager();
        $city           = $request->request->get('city');
        $autocomplete   = $em->getRepository('AppBundle:FranceRegion')->autocompleteByCity($city);
        return new JsonResponse($autocomplete);
    }

    /**
     * Get nearest city from GPS coordinate
     *
     * @Route("/nearest/city", name="region.nearest.city")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchNearestCityFromCoordinate(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $latitude   = $request->request->get('lat');
        $longitude  = $request->request->get('lng');

        $region     = $em->getRepository('AppBundle:FranceRegion')->getDistanceByCoordinate($latitude,$longitude, 1000000);
        return new JsonResponse(['city' => $region['city']]);
    }

}
