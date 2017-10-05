<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ObservationController extends Controller
{
    /**
     * @Route("/observation/creation", name="observation.create")
     * @Method({"GET","POST"})
     */
    public function createAction(Request $request)
    {
        return $this->render('observation/create.html.twig');
    }

    /**
     * @Route("/observation/carte", name="observation.map")
     * @Method({"GET"})
     */
    public function showMapAction()
    {
        return $this->render('observation/map.html.twig');
    }

    /**
     * @Route("/observation/carte/{id}", name="observation.detail", requirements={"id": "\d+"})
     * @Method({"GET"})
     */
    public function showDetailAction(Request $request)
    {
        return $this->render('observation/detail.html.twig');
    }

    /**
     * @Route("/observation/liste", name="observation.list")
     * @Method({"GET"})
     */
    public function showListAction()
    {
        return $this->render('observation/list.html.twig');
    }
}
