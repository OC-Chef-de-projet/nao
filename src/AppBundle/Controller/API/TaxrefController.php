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
 * @Route("/API/taxref")
 */
class TaxrefController extends Controller
{

    /**
     * Get list of searching name
     *
     * @Route("/search/name", name="taxref.search.name")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchNameOfTaxrefAction(Request $request)
    {
        $em             = $this->getDoctrine()->getManager();
        $name           = $request->request->get('name');
        $autocomplete   = $em->getRepository('AppBundle:Taxref')->autocompleteByCommonName($name);
        return new JsonResponse($autocomplete);
    }

}
