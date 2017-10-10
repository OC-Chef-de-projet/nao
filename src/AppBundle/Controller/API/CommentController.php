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
 * Class CommentController
 *
 * @Route("/API/comment")
 *
 * @package AppBundle\Controller
 */
class CommentController extends Controller
{


    /**
     * Paginate comment list (ajax)
     *
     * @Route("/paginate", name="api_comment_paginate")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function paginateAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('AppBundle:Comment')->getCommentsToModerate(
            $request->request->get('page'),
            $this->getParameter('list_limit')
        );

        $form = $this->createForm(ConfirmType::class,null, ['url' => $this->generateUrl('admin_comment_confirmation', array('action' => '--','id' => 0))]);
        $form->handleRequest($request);


        $html = $this->render('admin/comment/list.html.twig', [
            'cmtlist' => $comments,
            'form' =>  $form->createView()
        ])->getContent();

        return new JsonResponse(['html' => $html]);
    }
}

