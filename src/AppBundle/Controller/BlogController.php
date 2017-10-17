<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;

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
    public function indexAction(Request $request, $page = 1, $status = Post::PUBLISHED)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AppBundle:Post')->getPostsByStatus($page,$status,$this->getParameter('list_limit'));
        return $this->render('blog/index.html.twig', array(
            'paginate' => $this->container->get('app.post')->getPagination($posts,$page),
            'postlist' => $posts->getIterator(),
        ));
    }

    /**
     * @Route("/{id}/{slug}", name="blog.detail", requirements={"id": "\d+"})
     * @Method({"GET"})
     * @ParamConverter("post", options={"mapping": {"id": "id"}})
     */
    public function showDetailAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('blog/detail.html.twig', array(
            'article'   => $post,
            'comments'  => $em->getRepository('AppBundle:Comment')->getCommentsValidate()
        ));
    }

}
