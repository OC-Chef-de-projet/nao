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
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Class LoadUserData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadUserData extends Fixture implements FixtureInterface, ContainerAwareInterface
{

    /**
     * Create users*
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // bin/console doctrine:fixtures:load
        // Add admin
        $user = new User();
        $user->setName('Bobby');
        $user->setEmail('admin@test.com');
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('test', $user->getSalt()));
        $user->setRole('ROLE_ADMIN');
        $user->setInactive(false);
        $user->setImagePath('bobby.jpg');
        $manager->persist($user);
        $this->addReference('admin', $user);
        $manager->flush();

        // Add editor
        $user = new User();
        $user->setName('Charlotte');
        $user->setEmail('natur@test.com');
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('test', $user->getSalt()));
        $user->setRole('ROLE_NATURALIST');
        $user->setInactive(false);
        $user->setImagePath('charlotte.jpg');
        $manager->persist($user);
        $this->addReference('naturalist', $user);
        $manager->flush();

        // Add contributor
        $user = new User();
        $user->setName('Johnny');
        $user->setEmail('obs@test.com');
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('test', $user->getSalt()));
        $user->setRole('ROLE_OBSERVER');
        $user->setInactive(false);
        $user->setImagePath('johnny.jpg');
        $this->addReference('observer', $user);
        $manager->persist($user);
        $manager->flush();

        for($i = 0 ; $i < 5 ; $i++ ) {
            // Add admin
            $user = new User();
            $user->setName('Administrateur_'.$i);
            $user->setEmail('admin'.$i.'@test.com');
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
            $user->setPassword($encoder->encodePassword('test', $user->getSalt()));
            $user->setRole('ROLE_ADMIN');
            $user->setInactive(true);
            $user->setImagePath('avatar-default.png');
            $manager->persist($user);
            $manager->flush();

            // Add editor
            $user = new User();
            $user->setName('Naturaliste_'.$i);
            $user->setEmail('natur'.$i.'@test.com');
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
            $user->setPassword($encoder->encodePassword('test', $user->getSalt()));
            $user->setRole('ROLE_NATURALIST');
            $user->setImagePath('avatar-default.png');
            $user->setInactive(true);

            $manager->persist($user);
            $manager->flush();

            // Add contributor
            $user = new User();
            $user->setName('Observateur_'.$i);
            $user->setEmail('obs'.$i.'@test.com');
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
            $user->setPassword($encoder->encodePassword('test', $user->getSalt()));
            $user->setRole('ROLE_OBSERVER');
            $user->setInactive(true);
            $user->setImagePath('avatar-default.png');


            $manager->persist($user);
            $manager->flush();
        }
    }
}