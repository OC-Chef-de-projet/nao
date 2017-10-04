<?php

namespace AppBundle\Controller;

use Symfony\Component\Translation\Translator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class HomeController
 *
 * @package AppBundle\Controller
 */
class HomeController extends Controller
{
    /**
     * HomePage
     * @Route("/", name="homepage")
     * @Method({"GET"})
     *
     * @param Request $request  Http request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('homepage.html.twig', array(
            'observations'   => $this->container->get('app.obs')->getLastObersations(3)
        ));
    }
}

