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
    public function searchNearestCityFromCoordinateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $latitude   = $request->request->get('lat');
        $longitude  = $request->request->get('lng');

        for ($d= 500; $d <= 50000; $d+= 500){
            $region     = $em->getRepository('AppBundle:FranceRegion')->getDistanceByCoordinate($latitude,$longitude, $d);
            if(!is_null($region)){
                break;
            }
        }
        return new JsonResponse(['city' => $region['city']]);
    }

}
