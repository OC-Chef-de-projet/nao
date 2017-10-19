<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Notification;

/**
 * Class NotificationRepository
 *
 * @package AppBundle\Repository
 */
class NotificationRepository extends EntityRepository
{
    public function deleteByUser($user_id)
    {
        $query = $this->createQueryBuilder('n')
            ->delete()
            ->where('n.fromUser = :user_id')
            ->orWhere('n.toUser = :user_id')
            ->setParameter('user_id',$user_id)
            ->getQuery()
        ;
        return 1 === $query->getScalarResult();
    }
}
