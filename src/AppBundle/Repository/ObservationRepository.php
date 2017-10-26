<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Observation;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class NotificationRepository
 *
 * @package AppBundle\Repository
 */
class ObservationRepository extends EntityRepository
{
    public function deleteByUser($user_id)
    {
        $query = $this->createQueryBuilder('o')
            ->delete()
            ->where('o.user = :user_id')
            ->orWhere('o.naturalist = :user_id')
            ->setParameter('user_id',$user_id)
            ->getQuery()
        ;
        return 1 === $query->getScalarResult();
    }

    /**
     * get observation to be validated
     *
     * @param $currentPage
     * @param int $limit
     * @return Paginator
     */
    public function getWaitingValidation($currentPage,$limit = 50)
    {
        $query = $this->createQueryBuilder('o')
            ->where('o.status = :status')
            ->setParameter('status',Observation::WAITING)
            ->orderBy('o.watched', 'ASC')
            ->getQuery();

        $paginator = $this->paginate($query, $currentPage, $limit);
        return $paginator;
    }

    /**
     * Get my validated observation (role NATURALIST)
     *
     * @param $user
     * @param $currentPage
     * @param int $limit
     * @return Paginator
     */
    public function getValidateValidation($user, $currentPage,$limit = 50)
    {
        $query = $this->createQueryBuilder('o')
            ->where('o.status = :status')
            ->setParameter('status',Observation::VALIDATED)
            ->andWhere('o.naturalist = :user')
            ->setParameter('user',$user)
            ->orderBy('o.validated', 'DESC')
            ->getQuery();

        $paginator = $this->paginate($query, $currentPage, $limit);
        return $paginator;
    }

    public function getDeclineValidation($user, $currentPage,$limit = 50)
    {
        $query = $this->createQueryBuilder('o')
            ->where('o.status = :status')
            ->setParameter('status',Observation::REFUSED)
            ->andWhere('o.naturalist = :user')
            ->setParameter('user',$user)
            ->orderBy('o.validated', 'DESC')
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

    /**
     * Get my draft observation
     *
     * @param $currentPage
     * @param int $limit
     * @return Paginator
     */
    public function getMyDraftObservations($user, $currentPage, $limit = 50)
    {
        $query = $this->createQueryBuilder('o')
            ->where('o.status = :status')
            ->setParameter('status',Observation::DRAFT)
            ->andWhere('o.user = :user')
            ->setParameter('user',$user)
            ->orderBy('o.watched', 'DESC')
            ->getQuery();

        $paginator = $this->paginate($query, $currentPage, $limit);
        return $paginator;
    }

    /**
     * Get my validate observation
     *
     * @param $currentPage
     * @param int $limit
     * @return Paginator
     */
    public function getMyValidateObservations($user, $currentPage, $limit = 50)
    {
        $query = $this->createQueryBuilder('o')
            ->where('o.status = :status')
            ->setParameter('status',Observation::VALIDATED)
            ->andWhere('o.user = :user')
            ->setParameter('user',$user)
            ->orderBy('o.watched', 'DESC')
            ->getQuery();

        $paginator = $this->paginate($query, $currentPage, $limit);
        return $paginator;
    }

    /**
     * Get my wainting observation
     *
     * @param $currentPage
     * @param int $limit
     * @return Paginator
     */
    public function getMyWaitingObservations($user, $currentPage, $limit = 50)
    {
        $query = $this->createQueryBuilder('o')
            ->where('o.status = :status')
            ->setParameter('status',Observation::WAITING)
            ->andWhere('o.user = :user')
            ->setParameter('user',$user)
            ->orderBy('o.watched', 'DESC')
            ->getQuery();

        $paginator = $this->paginate($query, $currentPage, $limit);
        return $paginator;
    }
}
