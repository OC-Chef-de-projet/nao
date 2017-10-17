<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class ObservationController
 * @package AppBundle\Controller
 * @Route("/observation")
 */
class ObservationController extends Controller
{
    /**
     * @Route("/creation", name="observation.create")
     * @Security("is_granted('ROLE_OBSERVER')")
     * @Method({"GET"})
     */
    public function createAction()
    {
        return $this->render('observation/create.html.twig');
    }

    /**
     * @Route("/carte", name="observation.map")
     * @Method({"GET"})
     */
    public function showMapAction()
    {
        return $this->render('observation/map.html.twig');
    }

    /**
     * @Route("/carte/{id}", name="observation.detail", requirements={"id": "\d+"})
     * @Method({"GET"})
     */
    public function showDetailAction()
    {
        return $this->render('observation/detail.html.twig');
    }

    /**
     * @Route("/liste", name="observation.list")
     * @Method({"GET"})
     */
    public function showListAction()
    {
        return $this->render('observation/list.html.twig');
    }

    /**
     * @Route("/mes-observations/brouillon", name="observation.me.draft")
     * @Security("is_granted('ROLE_OBSERVER')")
     * @Method({"GET"})
     */
    public function showDraftAction()
    {
        return $this->render('observation/me/draft.html.twig');
    }

    /**
     * @Route("/mes-observations/valide", name="observation.me.validate")
     * @Security("is_granted('ROLE_OBSERVER')")
     * @Method({"GET"})
     */
    public function showValidateAction()
    {
        return $this->render('observation/me/validate.html.twig');
    }

    /**
     * @Route("/mes-observations/en-attente", name="observation.me.waiting")
     * @Security("is_granted('ROLE_OBSERVER')")
     * @Method({"GET"})
     */
    public function showWaitingAction()
    {
        return $this->render('observation/me/waiting.html.twig');
    }

    /**
     * @Route("/validation/en-attente", name="observation.validation.waiting")
     * @Security("is_granted('ROLE_NATURALIST')")
     * @Method({"GET"})
     */
    public function showWaitingValidationAction()
    {
        return $this->render('observation/validation/waiting.html.twig');
    }

    /**
     * @Route("/validation/vos-validations", name="observation.validation.validate")
     * @Security("is_granted('ROLE_NATURALIST')")
     * @Method({"GET"})
     */
    public function showValidateValidationAction()
    {
        return $this->render('observation/validation/validate.html.twig');
    }

    /**
     * @Route("/validation/vos-refus", name="observation.validation.decline")
     * @Security("is_granted('ROLE_NATURALIST')")
     * @Method({"GET"})
     */
    public function showDeclineValidationAction()
    {
        return $this->render('observation/validation/refuse.html.twig');
    }
}
