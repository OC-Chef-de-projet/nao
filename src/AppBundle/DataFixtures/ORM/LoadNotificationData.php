<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Sylvain
 * Date: 30-07-17
 * Time: 22:04
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Notification;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Class LoadUserData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadNotificationData extends Fixture implements FixtureInterface, ContainerAwareInterface
{


    /**
     * Create users*
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $notice = new Notification();
        $notice->setContent('Notification ADMIN -> NATURALIST');
        $notice->setFromUser($this->getReference('admin'));
        $notice->setToUser($this->getReference('naturalist'));
        $manager->persist($notice);
        $manager->flush();

        $notice = new Notification();
        $notice->setStatus(false);
        $notice->setContent('Notification ADMIN -> OBSERVER');
        $notice->setFromUser($this->getReference('admin'));
        $notice->setToUser($this->getReference('observer'));
        $manager->persist($notice);
        $manager->flush();

        $notice = new Notification();
        $notice->setStatus(false);
        $notice->setContent('Notification OBSERVER -> ADMIN');
        $notice->setFromUser($this->getReference('observer'));
        $notice->setToUser($this->getReference('admin'));
        $manager->persist($notice);
        $manager->flush();

        $notice = new Notification();
        $notice->setStatus(false);
        $notice->setContent('Notification OBSERVER -> NATURALIST');
        $notice->setFromUser($this->getReference('observer'));
        $notice->setToUser($this->getReference('naturalist'));
        $manager->persist($notice);
        $manager->flush();

        $notice = new Notification();
        $notice->setStatus(false);
        $notice->setContent('Notification NATURALIST -> ADMIN');
        $notice->setFromUser($this->getReference('naturalist'));
        $notice->setToUser($this->getReference('admin'));
        $manager->persist($notice);
        $manager->flush();

        $notice = new Notification();
        $notice->setStatus(false);
        $notice->setContent('Notification NATURALIST -> OBSERVER');
        $notice->setFromUser($this->getReference('naturalist'));
        $notice->setToUser($this->getReference('observer'));
        $manager->persist($notice);
        $manager->flush();

    }

    public function getDependencies()
    {
        return array(
            LoadUserData::class,
        );
    }

}