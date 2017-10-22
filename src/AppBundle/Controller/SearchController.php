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
    public function globalSearchAction(Request $request, $page = 1, $pattern ='')
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AppBundle:Post')->searchPosts($page,$this->getParameter('list_limit'),$request->query->get('search'));

        dump($posts->getIterator());
        return $this->render('search.html.twig', array(
            'paginate' => $this->container->get('app.post')->getPagination($posts,$page),
            'postlist' => $posts->getIterator(),
            'pattern' => $request->query->get('search')
        ));

    }

}
