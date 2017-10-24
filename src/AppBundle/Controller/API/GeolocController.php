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
        $result         = array();

        $autocomplete   = $em->getRepository('AppBundle:FranceRegion')->autocompleteByCity($city);

        foreach ($autocomplete as $value){
            $result[]['text'] = substr($value['code'],0,2) .' - '. $value['city'];
        }

        return new JsonResponse($result);
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
        $city = '';

        for ($d= 500; $d <= 50000; $d+= 500){
            $region     = $em->getRepository('AppBundle:FranceRegion')->getDistanceByCoordinate($latitude,$longitude, $d);
            if(!is_null($region)){
                $city = substr($region['code'],0,2) .' - '. $region['city'];
                break;
            }
        }
        return new JsonResponse(['city' => $city]);
    }

}
