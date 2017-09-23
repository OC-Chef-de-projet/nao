<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UserListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if($entity instanceof User) {
            $entity->setCreated(new \DateTime());
        }
    }
}