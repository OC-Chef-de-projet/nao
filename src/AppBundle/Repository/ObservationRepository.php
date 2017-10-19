<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Observation;

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
}
