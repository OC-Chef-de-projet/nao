<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Sylvain
 * Date: 30-07-17
 * Time: 22:04
 */

namespace AppBundle\DataFixtures\ORM\data;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\FranceRegion;

/**
 * Class LoadUserData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadFranceRegionData implements FixtureInterface, ContainerAwareInterface
{

    private $container;

    /**
     * Container
     *
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Create users*
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // bin/console doctrine:fixtures:load

        $csv = fopen(dirname(__FILE__).'/FranceRegion.csv', 'r');

        $first = true;
        $count = 0;
       while (!feof($csv)) {


            if($first){
                $first = false;
                continue;
            }
            echo "$count\n";
            $count++;
           if($count >= 100)break;
            $line = fgetcsv($csv,0,';');
            if(empty($line)){
                continue;
            };
            $franceRegion = new FranceRegion();
            $franceRegion->setRegion($line[0]);
            $franceRegion->setRegionCode($line[1]);
            $franceRegion->setRegionName($line[2]);
            $franceRegion->setChiefTown($line[3]);
            $franceRegion->setCountyCode($line[4]);
            $franceRegion->setCounty($line[5]);
            $franceRegion->setPrefecture($line[6]);
            $franceRegion->setDisctrictCode($line[7]);
            $franceRegion->setCity($line[8]);
            $franceRegion->setPostcode($line[9]);
            $franceRegion->setInsee($line[10]);
            $franceRegion->setLatitude($line[11]);
            $franceRegion->setLongitude($line[12]);
            $franceRegion->setDistance($line[13]);

            $manager->persist($franceRegion);
            $manager->flush();
        }
        fclose($csv);
    }
}

