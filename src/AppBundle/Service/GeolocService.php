<?php

namespace AppBundle\Service;

use AppBundle\Entity\Observation;
use Doctrine\ORM\EntityManager;
use AppBundle\Service\GPS;

/**
 * Class GeolocService
 *
 * @package AppBundle\Service
 */
class GeolocService
{
    private $em;
    private $gps;

    /**
     * GeolocService constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, GPSService $gps)
    {
        $this->em = $em;
        $this->gps = $gps;
    }

    /**
     * Get the most near city
     *
     * get near region
     *
     * @return mixed
     */
    public function getFranceRegion($latitude, $longitude){


        $distance = 10000000;
        $region_id = 0;
        for($i = 1 ; $i < 40000 ; $i += 500){
            $regions = $this->em->getRepository('AppBundle:FranceRegion')->getAll($i, 500);
            if(!$regions){
                break;
            }
            foreach ($regions as $region) {
                $lt2 = $region->getLatitude();
                $lg2 = $region->getLongitude();
                $d = $this->gps->getDistance($region->getLatitude(), $region->getLongitude(), $latitude, $longitude);
                if ($d < $distance) {
                    $distance = $d;
                    $region_id = $region->getId();
                    if($distance == 0){
                        break;
                    }
                }
            }
            $this->em->clear();
        }
        $region = $this->em->getRepository('AppBundle:FranceRegion')->findOneById($region_id);;
        $region->setDistance($distance);
        return $region;
    }

    /**
     * Get all observation rouding a GPS position
     *
     * @param $latitute
     * @param $longitude
     * @param $distance
     */
    public function getNearest($latitude, $longitude, $distance)
    {

        $observations = $this->em->getRepository('AppBundle:Observation')->findBy([
                'status' => Observation::VALIDATED
            ]
        );

        $result = [];
        foreach($observations as $observation){
            $d = $this->gps->getDistance($observation->getLatitude(), $observation->getLongitude(), $latitude, $longitude);
            if($d <= 10){
                $result[] = $observation;
            }
        }
        return $result;

    }
}
