<?php

namespace AppBundle\Service;

use AppBundle\Entity\Observation;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class ObservationService
 * @package AppBundle\Service
 */
class ObservationService
{

    private $em;

    /**
     * ObservationService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     * @param $email
     * @param string $url
     *
     * @return array
     */
    public function getlist($email, $url = '')
    {

        $user = $this->em->getRepository('AppBundle:User')->findOneByEmail($email);
        if (!$user) {
            return [];
        }

        $obs = $this->em->getRepository('AppBundle:Observation')->findByUser($user);
        if (!$obs) {
            return [];
        }

        $response = [];
        foreach ($obs as $ob) {
            $response[] = $this->obsArray($ob,$url);
        }
        return $response;
    }

    public function add($email, $observation)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneByEmail($email);
        if (!$user) {
            return [];
        }

        $obs = new Observation();

        $obs->setUser($user);

        if(isset($observation['place'])){
            $obs->setPlace($observation['place']);
        }
        if(isset($observation['watched'])){

            $obs->setWatched(new \DateTime($observation['watched']));
        }
        if(isset($observation['latitude'])){
            $obs->setLatitude($observation['latitude']);
        }
        if(isset($observation['longitude'])){
            $obs->setLongitude($observation['longitude']);
        }
        if(isset($observation['comments'])){
            $obs->setComments($observation['comments']);
        }
        if(isset($observation['individuals'])){
            $obs->setIndividuals($observation['individuals']);
        }

        $obs->setStatus(Observation::WAITING);


        /*
        if(isset($observation['image'])){
            $obs->setImage($observation['image']);
        }
        */

        // TAXREF
        if(isset($observation['TAXREF_id'])){
            $taxref = $this->em->getRepository('AppBundle:Taxref')->findOneById($observation['TAXREF_id']);
            if($taxref) {
                $obs->setTaxref($taxref);
            }
        }
        $this->em->persist($obs);
        $this->em->flush();
        return $this->obsArray($obs);
    }


    public function get($id)
    {
        return $this->obsArray($this->em->getRepository('AppBundle:Observation')->findOneById($id));
    }

    private function obsArray(Observation $ob,$url = '')
    {
        $naturalist = '';
        $n = $ob->getNaturalist();
        if ($n) {
            $naturalist = $n->getName();
        }

        $image = $ob->getImagePath();
        if($image) {
            $image = $url . '/images/obs/' . $image;
        }
        $obs = [
            'place' => $ob->getPlace(),
            'validated' => $ob->getValidated(),
            'watched' => $ob->getWatched(),
            'latitude' => $ob->getLatitude(),
            'longitude' => $ob->getLongitude(),
            'imagePath' => $image,
            'comments' => $ob->getComments(),
            'individuals' => $ob->getIndividuals(),
            'naturalist' => $naturalist,
            'status' => $ob->getStatus(),
            'statusText' => $ob->getStatusString(),
            'TAXREF' => [
                'regnum' => $ob->getTaxref()->getRegnum(),
                'phylum' => $ob->getTaxref()->getPhylum(),
                'classis' => $ob->getTaxref()->getClassis(),
                'ordo' => $ob->getTaxref()->getOrdo(),
                'familia' => $ob->getTaxref()->getFamilia(),
                'scientificId' => $ob->getTaxref()->getScientificId(),
                'taxonId' => $ob->getTaxref()->getTaxonId(),
                'taxonRefId' => $ob->getTaxref()->getTaxonRefId(),
                'taxonRank' => $ob->getTaxref()->getTaxonRank(),
                'taxonSc' => $ob->getTaxref()->getTaxonSc(),
                'author' => $ob->getTaxref()->getAuthor(),
                'fullname' => $ob->getTaxref()->getFullname(),
                'validName' => $ob->getTaxref()->getValidName(),
                'commonName' => $ob->getTaxref()->getCommonName()
            ]
        ];
        return $obs;
    }
}
