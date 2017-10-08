<?php

namespace AppBundle\Service;

use AppBundle\Entity\Newsletter;
use Doctrine\ORM\EntityManager;

/**
 * Class NewsletterService
 * @package AppBundle\Service
 */
class NewsletterService
{

    private $em;

    /**
     * NewsletterService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Registration to newsletter
     */
    public function subscribe()
    {

    }

}

