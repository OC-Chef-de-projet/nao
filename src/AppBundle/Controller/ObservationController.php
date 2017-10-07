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
        return $this->render('observation/me/list.html.twig');
    }

    /**
     * @Route("/observation/mes-observations/brouillon", name="observation.me.draft")
     * @Method({"GET"})
     */
    public function showDraftAction()
    {
        return $this->render('observation/me/draft.html.twig');
    }

    /**
     * @Route("/observation/mes-observations/valide", name="observation.me.validate")
     * @Method({"GET"})
     */
    public function showValidateAction()
    {
        return $this->render('observation/me/validate.html.twig');
    }

    /**
     * @Route("/observation/mes-observations/attente", name="observation.me.waiting")
     * @Method({"GET"})
     */
    public function showWaintingAction()
    {
        return $this->render('observation/me/waiting.html.twig');
    }
}
