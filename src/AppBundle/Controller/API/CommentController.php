<?php

namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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

        $html = $this->render('admin/comment/list.html.twig', [
            'cmtlist' => $comments
        ])->getContent();

        return new JsonResponse(['html' => $html]);
    }
}

