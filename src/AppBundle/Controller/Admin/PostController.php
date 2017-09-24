<?php

namespace AppBundle\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Post;

class PostController extends Controller
{

    /**
     * Lists post entities.
     *
     */
    public function indexAction(Request $request, $page = 1, $status = Post::DRAFT )
    {

        $em = $this->getDoctrine()->getManager();

        $limit = 8;
        $posts = $em->getRepository('AppBundle:Post')->getPostsByStatus($page,$status,$limit);
        $totalPosts = $posts->count();
        $totalDisplayed = $posts->getIterator()->count();
        $maxPages = ceil($posts->count() / $limit);
        return $this->render('@AdminPost/index.html.twig', array(
            'bodyClass' => 'background-2',
            'tabs' => $this->container->get('app.post')->getPostsTabs($status),
            'posts' => $posts->getIterator(),
            'totalPosts' => $totalPosts,
            'totalDisplayed' => $totalDisplayed,
            'current' => $status,
            'maxPages' => $maxPages,
            'breadcrumb' => $this->container->get('app.post')->getIndexBreadcrumb()
        ));
    }

    /**
     * Displays a form to edit an existing post entity.
     *
     */
    public function editAction(Request $request, Post $post)
    {
        $PostService = $this->get('service_post');
        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createForm(PostType::class, $post,['status' => $PostService->getStatusOptions($user,$post)]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('@AdminPost/edit.html.twig', array(
            'title' => $PostService->getActionTitle($user, $post),
            'post' => $post,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


}
