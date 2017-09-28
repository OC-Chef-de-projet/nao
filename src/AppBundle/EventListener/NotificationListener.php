<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Sylvain
 * Date: 21-08-17
 * Time: 10:10
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Notification;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Class NotificationListener
 *
 * @package AppBundle\EventListener
 */
class NotificationListener
{


    /**
     * Creation mode
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if($entity instanceof Notification) {
            $entity->setCreated(new \DateTime());
            $entity->setStatus(0);
        }
    }

}

