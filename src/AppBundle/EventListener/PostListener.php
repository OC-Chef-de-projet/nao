<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Sylvain
 * Date: 21-08-17
 * Time: 10:10
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Post;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class PostListener
{

    /**
     * Update publishedDate
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Post) {
            return;
        }
        // Update published date only if asked
        // but don't update published date
        // if already in published state
        if ($args->hasChangedField('status') && $args->getNewValue('status') === Post::PUBLISHED) {
            $entity->setPublishedAt(new \DateTime());
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if($entity instanceof Post) {
            if($entity->getStatus() === Post::PUBLISHED){
                $entity->setPublishedAt(new \DateTime());
            }
            $entity->setCreatedAt(new \DateTime());
        }
    }

}

