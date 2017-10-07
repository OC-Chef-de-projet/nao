<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class BlogController
 * @package AppBundle\Controller
 * @Route("/blog")
 */
class BlogController extends Controller
{

    /**
     * @Route("/", name="blog")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->render('blog/index.html.twig');
    }

    /**
     * @Route("/{id}/{slug}", name="blog.detail", requirements={"id": "\d+"})
     * @Method({"GET"})
     */
    public function showDetailAction()
    {
        return $this->render('blog/detail.html.twig');
    }

}
