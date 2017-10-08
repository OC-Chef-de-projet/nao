<?php
namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Post;
use AppBundle\Form\Type\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\Type\ConfirmType;

/**
 * Class PostController
 *
 * @Route("/admin/post")
 *
 * @package AppBundle\Controller\Admin
 */
class PostController extends Controller
{

    /**
     * List posts
     *
     * @Route("/{page}/{status}", requirements={"page" = "\d+"} , defaults={"page" = 1, "status" = Post::PUBLISHED}, name="admin_post_index")
     * @Method({"GET"})
     *
     * @param Request $request  Httpd request
     * @param int $page         Page number
     * @param int $status       Post status (Post::DRAFT, Post::PUBLISHED)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page = 1, $status = Post::PUBLISHED )
    {
        $em = $this->getDoctrine()->getManager();
        $c = $this->get('security.token_storage')->getToken()->getUser();
        $posts = $em->getRepository('AppBundle:Post')->getPostsByStatus($page,$status,$this->getParameter('list_limit'));
        $form = $this->createForm(ConfirmType::class,null, ['url' => $this->generateUrl('admin_post_confirmation', array('action' => '--','id' => 0))]);
        $form->handleRequest($request);

        return $this->render('@AdminPost/index.html.twig', [
            'token' => $this->container->get('lexik_jwt_authentication.jwt_manager')->create($c),
            'paginate' => $this->container->get('app.post')->getPagination($posts,$page),
            'postlist' => $posts->getIterator(),
            'form' =>  $form->createView()
        ]);
    }

    /**
     * Edit post
     *
     * @Route("/edit/{id}",  requirements={"id" = "\d+"}, name="admin_post_edit")
     * @Method({"GET","POST"})
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
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * New Post
     *
     * @Route("/new", name="admin_post_new")
     * @Method({"GET","POST"})
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
            'post' => $post,
            'form' => $form->createView()
        ]);
    }


    /**
     * Confirmation of deletion
     *
     * @Route("/confirm/{action}/{id}", requirements={"id" = "\d+"}, name="admin_post_confirmation")
     * @Method({"POST"})
     *
     * @param Request $request Httpd request
     * @param int $page Page number
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmAction(Request $request, $action, $id)
    {

        $this->container->get('app.post')->modifyPost($request->request->get('confirm'));
        return $this->redirectToRoute('admin_post_index');
    }
}
