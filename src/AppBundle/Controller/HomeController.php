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
        dump($request->getUriForPath('/uploads/myimage.jpg'));

        return $this->render('homepage.html.twig', [
            'page' => [
                'title'         => $this->get('translator')->trans('homepage_title'),
                'meta'          => [
                    'description'   => $this->get('translator')->trans('homepage_meta_description'),
                ],
                'headerClass'   => 'full-height bg2',
            ]
        ]);
    }
}

