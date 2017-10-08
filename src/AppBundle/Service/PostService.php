<?php

namespace AppBundle\Service;

use AppBundle\Entity\Post;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class PostService
 *
 * @package AppBundle\Service
 */
class PostService
{

    private $em;
    private $posts_directory;
    private $list_limit;

    /**
     * PostService constructor.
     *
     * @param EntityManager $em
     * @param $posts_directory
     * @param $list_limit
     */
    public function __construct(EntityManager $em, $posts_directory, $list_limit)
    {
        $this->em = $em;
        $this->posts_directory = $posts_directory;
        $this->list_limit = $list_limit;
    }

    /**
     * Return last Post
     *
     * @return Post Post
     */
    public function getLastPost()
    {
        $post = $this->em->getRepository('AppBundle:Post')->findOneBy(
            [
                'status' => Post::PUBLISHED
            ],
            [
                'publishedAt' => 'DESC'
            ]
        );
        return $post;
    }

    /**
     * Return X last posts
     *
     * @param  int $max Number of posts
     *
     * @return Array      Array of Post
     */
    public function getLastPosts($max)
    {
        $post = $this->em->getRepository('AppBundle:Post')->findBy(
            [
                'status' => Post::PUBLISHED
            ],
            [
                'publishedAt' => 'DESC'
            ],
            $max
        );
        return $post;
    }

    /**
     * Save existng post (from edit)
     *
     * @param Post $post Post entity
     * @param $form             Form
     *
     * @return bool
     */
    public function savePost(Post $post, Form $form)
    {
        if ($form->get('save_draft')->isClicked()) {
            $post->setPublishedAt(null);
            $post->setStatus(Post::DRAFT);
        }
        if ($form->get('save_published')->isClicked()) {
            $post->setStatus(Post::PUBLISHED);
        }

        $data = $form->getData();
        if (!is_string($data->getImagelink())) {
            $file = $post->getImagelink();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->posts_directory,
                $fileName
            );
            $post->setImagelink($fileName);
        }
        $this->em->persist($post);
        $this->em->flush();
        return true;
    }

    /**
     * Create new post
     *
     * @param Post $post Post
     * @param $form      Form
     */
    public function createPost(Post $post, Form $form)
    {
        if ($form->get('save_draft')->isClicked()) {
            $post->setPublishedAt(null);
            $post->setStatus(Post::DRAFT);
        }
        if ($form->get('save_published')->isClicked()) {
            $post->setStatus(Post::PUBLISHED);
        }

        $file = $post->getImagelink();
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($this->posts_directory, $fileName);
        $post->setImagelink($fileName);
        $this->em->persist($post);
        $this->em->flush();
    }

    /**
     * Get pagination parameters
     *
     * @param array $posts Post list
     * @param $page  int current page
     *
     * @return array  Current pagination
     */
    public function getPagination(Paginator $posts, $page)
    {

        $totalPosts = $posts->count();
        $totalDisplayed = $posts->getIterator()->count();
        $maxPages = ceil($posts->count() / $this->list_limit);

        return ['totalPosts' => $totalPosts,
            'totalDisplayed' => $totalDisplayed,
            'current' => $page,
            'maxPages' => $maxPages
        ];
    }

    /**
     * Remove post
     *
     * @param $data
     */
    public function modifyPost($data)
    {
        $post = $this->em->getRepository('AppBundle:Post')->findOneById($data['id']);
        if (!$post) return;

        switch ($data['action']) {
            case 'delete':
                $this->em->remove($post);
                $this->em->flush();
                break;
            default:
                return;
        }
    }
}

