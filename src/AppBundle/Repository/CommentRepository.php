<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Comment;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class CommentRepository
 *
 * @package AppBundle\Repository
 */
class CommentRepository extends EntityRepository
{
    public function getCommentsToModerate($currentPage,$limit = 50)
    {

        $query = $this->createQueryBuilder('c')
            ->where('c.status = :status')
            ->setParameter('status',Comment::WAITING)
            ->orderBy('c.created', 'DESC')
            ->getQuery();

        $paginator = $this->paginate($query, $currentPage, $limit);
        return $paginator;
    }

    /**
     * Paginator Helper
     *
     * @param $dql       DQL Query Object
     * @param int $page  Current page (defaults to 1)
     * @param int $limit The total number per page (defaults to 50)
     *
     * @return Paginator Object
     */
    public function paginate($dql, $page = 1, $limit = 50)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit
        return $paginator;
    }

    public function getCommentsValidate()
    {

        $query = $this->createQueryBuilder('c')
            ->where('c.status = :status')
            ->setParameter('status',Comment::ACCEPTED)
            ->orderBy('c.created', 'DESC')
            ->getQuery()
            ->getResult();

        return $query;
    }

}
