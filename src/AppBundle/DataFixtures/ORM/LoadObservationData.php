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
use AppBundle\Entity\Observation;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Class LoadUserData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadObservationData extends Fixture implements FixtureInterface, ContainerAwareInterface
{

    /**
     * Create Observations
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // bin/console doctrine:fixtures:load
        // Add admin

        $obs = new Observation();
        $obs->setStatus(Observation::WAITING);
        $obs->setComments('Observation faite par un particulier');
        $obs->setWatched(new \DateTime());
        $obs->setIndividuals(10);
        $obs->setLatitude(48.862725);
        $obs->setLongitude(2.287592000000018);
        $obs->setImagePath('buse-feroce.jpg');
        $obs->setPlace('En plein Paris');
        $obs->setUser($this->getReference('observer'));
        $obs->setTaxref($this->getReference('416687'));
        $manager->persist($obs);

        $obs = new Observation();
        $obs->setStatus(Observation::VALIDATED);
        $obs->setValidated(new \DateTime('-20 days'));
        $obs->setComments('Observation faite par un particulier et validée');
        $obs->setWatched(new \DateTime('-30 days'));
        $obs->setIndividuals(32);
        $obs->setLatitude(48.84736638904716);
        $obs->setLongitude(2.2899627685546875);
        $obs->setImagePath('buse-feroce.jpg');
        $obs->setPlace('Rue de Lourmel');
        $obs->setUser($this->getReference('observer'));
        $obs->setTaxref($this->getReference('416687'));
        $obs->setNaturalist($this->getReference('naturalist'));
        $manager->persist($obs);

        $obs = new Observation();
        $obs->setStatus(Observation::VALIDATED);
        $obs->setComments('Observation faite par un naturalist');
        $obs->setWatched(new \DateTime('yesterday'));
        $obs->setValidated(new \DateTime('now'));
        $obs->setIndividuals(10);
        $obs->setLatitude(43.19982700000001);
        $obs->setLongitude(2.140625);
        $obs->setImagePath('buse-feroce.jpg');
        $obs->setPlace('A Montréal');
        $obs->setUser($this->getReference('naturalist'));
        $obs->setTaxref($this->getReference('416687'));
        $manager->persist($obs);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LoadTaxrefData::class,
            LoadUserData::class
            );
    }

}