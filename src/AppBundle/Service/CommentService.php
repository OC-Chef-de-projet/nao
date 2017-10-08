<?php

namespace AppBundle\Service;

use AppBundle\Entity\Comment;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class CommentService
 *
 * @package AppBundle\Service
 */
class CommentService
{

    private $em;
    private $list_limit;

    /**
     * PostService constructor.
     *
     * @param EntityManager $em
     * @param $posts_directory
     * @param $list_limit
     */
    public function __construct(EntityManager $em,  $list_limit)
    {
        $this->em = $em;
        $this->list_limit = $list_limit;
    }



    /**
     * Get pagination parameters
     *
     * @param array $posts Post list
     * @param $page  int current page
     *
     * @return array  Current pagination
     */
    public function getPagination(Paginator $Comments, $page)
    {

        $totalComments = $Comments->count();
        $totalDisplayed = $Comments->getIterator()->count();
        $maxPages = ceil($Comments->count() / $this->list_limit);

        return ['totalPosts' => $totalComments,
            'totalDisplayed' => $totalDisplayed,
            'current' => $page,
            'maxPages' => $maxPages
        ];
    }
}

