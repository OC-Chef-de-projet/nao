<?php

namespace AppBundle\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Post;
use AppBundle\Form\Type\PostType;

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
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('save_draft')->isClicked()) {
                $post->setPublishedAt(null);
                $post->setStatus(Post::DRAFT);
            }
            if($form->get('save_published')->isClicked()) {
                $post->setStatus(Post::PUBLISHED);
            }

            $data = $form->getData();

            if(!is_string($data->getImagelink())) {
                $file = $post->getImagelink();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('posts_directory'),
                    $fileName
                );
                $post->setImagelink($fileName);
            }

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('@AdminPost/edit.html.twig', array(
            'bodyClass' => 'background-2',
            'post' => $post,
            'form' => $form->createView(),
            'breadcrumb' => $this->container->get('app.post')->getEditBreadcrumb()
        ));
    }

    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('save_draft')->isClicked()) {
                $post->setPublishedAt(null);
                $post->setStatus(Post::DRAFT);
            }
            if($form->get('save_published')->isClicked()) {
                $post->setStatus(Post::PUBLISHED);
            }


            $file = $post->getImagelink();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('posts_directory'), $fileName);
            $post->setImagelink($fileName);
            $em->persist($post);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('@AdminPost/new.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
            'breadcrumb' => $this->container->get('app.post')->getEditBreadcrumb()
        ));
    }

    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Boncommande entity.');
        }
        $em->remove($entity);
        $em->flush();
        return $this->redirectToRoute('admin_post_index');
    }

}
