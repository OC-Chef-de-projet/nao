<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\Comment;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class CommentListener
 *
 * @package AppBundle\EventListener
 */
class CommentListener
{
    /**
     * Insert Comment creation date
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if($entity instanceof Comment) {
            $entity->setCreated(new \DateTime());
        }
    }
}

