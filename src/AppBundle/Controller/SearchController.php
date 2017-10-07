<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SearchController extends Controller
{

    /**
     * @Route("/recherche", name="search.global")
     * @Method({"GET"})
     */
    public function globalSearchAction(Request $request)
    {
        return $this->render('search.html.twig');
    }

}
