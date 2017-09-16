<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserRepository extends EntityRepository
{
    public function getUsersByRole($currentPage,$role,$limit = 50)
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.role = :role')
            ->setParameter('role',$role)
            ->orderBy('u.name', 'ASC')
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
}
