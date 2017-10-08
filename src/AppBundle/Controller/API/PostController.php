<?php

namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\Type\ConfirmType;

/**
 * Class PostController
 *
 * @Route("/API/post")
 *
 * @package AppBundle\Controller
 */
class PostController extends Controller
{


    /**
     * Paginate post list (ajax)
     *
     * @Route("/paginate", name="api_post_paginate")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function paginateAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ConfirmType::class,null, ['url' => $this->generateUrl('admin_post_confirmation', array('action' => '--','id' => 0))]);
        $form->handleRequest($request);

        $posts = $em->getRepository('AppBundle:Post')->getPostsByStatus(
            $request->request->get('page'),
            $request->request->get('status'),
            $this->getParameter('list_limit')
        );

        $html = $this->render(':admin/post:list.html.twig', [
            'postlist' => $posts,
            'form' =>  $form->createView()

        ])->getContent();

        return new JsonResponse(['html' => $html]);
    }
}
