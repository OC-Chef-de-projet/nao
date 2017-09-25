<?php
namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Post;
use AppBundle\Form\Type\PostType;

/**
 * Class PostController
 *
 * @package AppBundle\Controller\Admin
 */
class PostController extends Controller
{

    /**
     * List posts
     *
     * @param Request $request  Httpd request
     * @param int $page         Page number
     * @param int $status       Post status (Post::DRAFT, Post::PUBLISHED)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page = 1, $status = Post::DRAFT )
    {

        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')->getPostsByStatus($page,$status,$this->getParameter('list_limit'));


        return $this->render('@AdminPost/index.html.twig', [
            'header' => [
                'bodyClass' => 'background-2',
                'tabs' => $this->container->get('app.post')->getPostsTabs($status),
                'breadcrumb' => $this->container->get('app.post')->getIndexBreadcrumb()
            ],
            'paginate' => $this->container->get('app.post')->getPagination($posts,$status),
            'posts' => $posts->getIterator()
        ]);
    }

    /**
     * Edit post
     *
     * @param Request $request  Http request
     * @param Post $post        Post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Post $post)
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->container->get('app.post')->savePost($post, $form);
            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('@AdminPost/edit.html.twig', [
            'header' => [
                'bodyClass' => 'background-2',
                'tabs' => $this->container->get('app.post')->getPostsTabs(Post::DRAFT),
                'breadcrumb' => $this->container->get('app.post')->getIndexBreadcrumb()
            ],
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * New Post
     *
     * @param Request $request  Http request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->container->get('app.post')->createPost($post, $form);
            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('@AdminPost/new.html.twig', [
            'header' => [
                'bodyClass' => 'background-2',
                'tabs' => $this->container->get('app.post')->getPostsTabs(Post::DRAFT),
                'breadcrumb' => $this->container->get('app.post')->getEditBreadcrumb()
            ],
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete post
     *
     * @param $id   Post id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post');
        }
        $em->remove($entity);
        $em->flush();
        return $this->redirectToRoute('admin_post_index');
    }

}
