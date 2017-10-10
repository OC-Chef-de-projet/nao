<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\FranceRegion;

/**
 * Class FranceRegionRepository
 *
 * @package AppBundle\Repository
 */
class FranceRegionRepository extends EntityRepository
{
    public function getAll($offset,$limit = 50)
    {

        $query = $this->createQueryBuilder('f')
            ->setFirstResult( $offset )
            ->setMaxResults( $limit )
            ->getQuery();
        return $query->getResult();
    }
}
