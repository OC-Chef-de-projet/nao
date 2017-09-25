<?php
namespace AppBundle\Service;

use AppBundle\Entity\Post;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

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
     * Save existng post (from edit)
     *
     * @param Post $post        Post entity
     * @param Request $request  Http request
     * @param $form             Form
     *
     * @return bool
     */
    public function savePost(Post $post, Request $request, $form)
    {
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
    public function createPost(Post $post, $form)
    {
        if($form->get('save_draft')->isClicked()) {
            $post->setPublishedAt(null);
            $post->setStatus(Post::DRAFT);
        }
        if($form->get('save_published')->isClicked()) {
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
     * @param $posts  Post
     * @param $status Post status (Post::DRAFT, Post::PUBLISHED)
     *
     * @return array  Current pagination
     */
    public function getPagination($posts,$status)
    {

        $totalPosts = $posts->count();
        $totalDisplayed = $posts->getIterator()->count();
        $maxPages = ceil($posts->count() / $this->list_limit);

        return ['totalPosts' => $totalPosts,
                'totalDisplayed' => $totalDisplayed,
                'current' => $status,
                'maxPages' => $maxPages
        ];

    }

    /**
     * Get breadcrumb elemnts for post admin index
     *
     * @return array
     */
    public function getIndexBreadcrumb()
    {
        $breadcrumb = [
            [
                'href' => '#',
                'text' => 'Accueil'
            ],
            [
                'href' => '#',
                'text' => 'Administration'
            ],
            [
                'href' => '#',
                'text' => 'Gestion des articles'
            ],
        ];
        return $breadcrumb;
    }

    /**
     * Get breadcrumb elemnts for post admin edit
     *
     * @return array
     */
    public function getEditBreadcrumb()
    {
        $breadcrumb = [
            [
                'href' => '#',
                'text' => 'Accueil'
            ],
            [
                'href' => '#',
                'text' => 'Administration'
            ],
            [
                'href' => '#',
                'text' => 'Gestion des articles'
            ],
            [
                'href' => '#',
                'text' => 'Rédiger un article'
            ],

        ];
        return $breadcrumb;
    }


    /**
     * Tabs for admin post index
     * 
     * @param $post
     * @return array
     */
    public function getPostsTabs($post)
    {
        $tabs = [
            Post::DRAFT => [
                'status' => Post::DRAFT,
                'text' => 'Brouillons',
                'active' => 0,
                'href' => ''
            ],
            Post::PUBLISHED => [
                'status' => Post::PUBLISHED,
                'text' => 'Publiés',
                'active' => 0,
                'href' => ''
            ]
        ];
        $tabs[$post]['active'] = 1;
        return $tabs;
    }
}

