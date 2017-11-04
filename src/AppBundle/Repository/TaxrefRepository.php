<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Taxref;

/**
 * Class NotificationRepository
 *
 * @package AppBundle\Repository
 */
class TaxrefRepository extends EntityRepository
{
    /**
     * Get autcomplete name
     *
     * @param $name
     * @return array
     */
    public function autocompleteByCommonName($name)
    {
        return $this->createQueryBuilder('t')
            ->select('t.common_name AS text', 't.taxon_sc AS latin')
            ->where('t.common_name LIKE :pattern')->setParameter('pattern', ''.$name.'%')
            ->getQuery()
            ->getResult();
    }
}
