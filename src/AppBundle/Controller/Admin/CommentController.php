<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Form\Type\ConfirmType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Comment;
use AppBundle\Form\Type\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Class CommentController
 *
 * @Route("/admin/comment")
 *
 * @package AppBundle\Controller\Admin
 */
class CommentController extends Controller
{

    /**
     * List Comment to moderate
     *
     * @Route("/{page}", requirements={"page" = "\d+"} , defaults={"page" = 1}, name="admin_comment_index")
     * @Method({"GET"})
     *
     * @param Request $request Httpd request
     * @param int $page Page number
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page = 1)
    {

        $em = $this->getDoctrine()->getManager();
        $c = $this->get('security.token_storage')->getToken()->getUser();
        $comments = $em->getRepository('AppBundle:Comment')->getCommentsToModerate($page, $this->getParameter('list_limit'));
        $form = $this->createForm(ConfirmType::class,null, ['url' => $this->generateUrl('admin_comment_confirmation', array('action' => '--','id' => 0))]);
        $form->handleRequest($request);

        return $this->render('@Admin/comment/index.html.twig', [
            'token' => $this->container->get('lexik_jwt_authentication.jwt_manager')->create($c),
            'paginate' => $this->container->get('app.cmt')->getPagination($comments, $page),
            'cmtlist' => $comments->getIterator(),
            'form' =>  $form->createView()
        ]);
    }

    /**
     * Confirmation of deletion or authorize
     *
     * @Route("/confirm/{action}/{id}", requirements={"id" = "\d+"}, name="admin_comment_confirmation")
     * @Method({"POST"})
     *
     * @param Request $request Httpd request
     * @param int $page Page number
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmAction(Request $request, $action, $id)
    {
        $this->container->get('app.cmt')->modifyComment($request->request->get('comment_confirm'));
        return $this->redirectToRoute('admin_comment_index');
    }
}
