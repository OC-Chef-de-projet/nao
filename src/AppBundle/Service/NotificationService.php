<?php
/**
 * Created by PhpStorm.
 * User: psa
 * Date: 16/09/17
 * Time: 09:00
 */

namespace AppBundle\Service;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Notification;
use AppBundle\Service\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class NotificationService
 *
 * @package AppBundle\Service
 */
class NotificationService
{

    private $em;
    private $ts;

    /**
     * NotificationService constructor.
     *
     * @param EntityManager $em
     * @param TokenStorage $ts
     */
    public function __construct(EntityManager $em, TokenStorage $ts)
    {
        $this->em = $em;
        $this->ts = $ts;
    }


    /**
     * Get all not readed notifications of the current logged user
     *
     * @return Notification[]|array
     */
    public function getNotifications()
    {
        $user = $this->ts->getToken()->getUser();
        $repository = $this->em->getRepository(Notification::class);
        return $repository->findBy(
            [
                'toUser' => $user,
                'status' => 0
            ],
            [
                'created' => 'DESC'
            ]
        );

    }

    /**
     * Mark the notification as read
     *
     * @param Notification $notification
     */
    public function setAsRead(Notification $notification)
    {
        $notification->setStatus(true);
        $notification->setWatched(new \DateTime());
        $this->em->persist($notification);
        $this->em->flush();

    }

    /**
     *  Mark all notifications as read
     */
    public function setAsReadAll()
    {
        foreach($this->getNotifications() as $notification){
            $notification->setStatus(true);
            $notification->setWatched(new \DateTime());
            $this->em->persist($notification);
        }
        $this->em->flush();
    }

    /**
     * Get lastest notifications of the current logged user
     *
     * @return Notification[]|array
     */
    public function getLastNotifications($max)
    {
        $user = $this->ts->getToken()->getUser();
        $repository = $this->em->getRepository(Notification::class);
        return $repository->findBy( ['toUser' => $user], ['created' => 'DESC'], $max);

    }

    /**
     * Return count of not readed notifications
     *
     * @return int
     */
    public function count()
    {
        return count($this->getNotifications());
    }

    /**
     * Delete all notifications for user
     *
     * @param User $user
     */
    public function deleteNotificationsForUser(User $user){
        $this->em->getRepository(Notification::class)->deleteByUser($user->getId());
    }
}

