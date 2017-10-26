<?php

namespace AppBundle\Service;

use AppBundle\Entity\FranceRegion;
use AppBundle\Entity\Observation;
use AppBundle\Entity\Taxref;
use AppBundle\Entity\User;
use AppBundle\Entity\Notification;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class ObservationService
 * @package AppBundle\Service
 */
class ObservationService
{

    private $em;
    private $ts;
    private $list_limit;
    private $observations_directory;

    /**
     * ObservationService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, TokenStorage $ts, $list_limit, $observations_directory)
    {
        $this->em = $em;
        $this->ts = $ts;
        $this->list_limit = $list_limit;
        $this->observations_directory = $observations_directory;
    }

    /**
     * Return X last observation
     *
     * @param  int $max Number of observation
     *
     * @return Array      Array of Observation
     */
    public function getLastObersations($max)
    {
        $obs = $this->em->getRepository('AppBundle:Observation')->findBy(
            [
                'status' => Observation::VALIDATED
            ],
            [
                'validated' => 'DESC'
            ],
            $max
        );
        return $obs;
    }

    /**
     * Get observation list
     *
     * @param string $url
     * @return array
     */
    public function getlist($url = '')
    {

        $user = $this->ts->getToken()->getUser();
        if (!$user) {
            return [];
        }

        $obs = $this->em->getRepository('AppBundle:Observation')->findByUser($user);
        if (!$obs) {
            return [];
        }

        $response = [];
        foreach ($obs as $ob) {
            $response[] = $this->obsArray($ob, $url);
        }
        return $response;
    }

    /**
     * Convert an observation entity to an array (not all fields)
     *
     * @param Observation $ob
     * @param string $url
     *
     * @return array
     */
    private function obsArray(Observation $ob, $url = '')
    {
        $naturalist = '';
        $n = $ob->getNaturalist();
        if ($n) {
            $naturalist = $n->getName();
        }

        $image = $ob->getImagePath();
        if ($image) {
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

    /**
     * Add an observation
     *
     * @param $observation
     * @return array
     */
    public function add($observation)
    {
        $user = $this->ts->getToken()->getUser();

        if (!$user) {
            return [];
        }

        $obs = new Observation();

        $obs->setUser($user);

        if (isset($observation['place'])) {
            $obs->setPlace($observation['place']);
        }
        if (isset($observation['watched'])) {

            $obs->setWatched(new \DateTime($observation['watched']));
        }
        if (isset($observation['latitude'])) {
            $obs->setLatitude($observation['latitude']);
        }
        if (isset($observation['longitude'])) {
            $obs->setLongitude($observation['longitude']);
        }
        if (isset($observation['comments'])) {
            $obs->setComments($observation['comments']);
        }
        if (isset($observation['individuals'])) {
            $obs->setIndividuals($observation['individuals']);
        }

        $obs->setStatus(Observation::WAITING);


        // TAXREF
        if (isset($observation['TAXREF_id'])) {
            $taxref = $this->em->getRepository('AppBundle:Taxref')->findOneById($observation['TAXREF_id']);
            if ($taxref) {
                $obs->setTaxref($taxref);
            }
        }
        $this->em->persist($obs);
        $this->em->flush();

        // Process image after save, because we need to use the observation id as filename
        $filename = $obs->getId() . '_' . $user->getId() . '.jpg';


        if (isset($observation['image']) && !empty($observation['image'])) {
            $data = base64_decode($observation['image']);
            file_put_contents('./images/obs/' . $filename, $data);
        } else {
            copy('images/obs/default-image_observation.jpg', 'images/obs/' . $filename);
        }
        $obs->setImagePath($filename);
        $this->em->persist($obs);
        $this->em->flush();
        return $this->obsArray($obs);
    }

    /**
     * Delete all observations for user
     *
     * @param User $user
     */
    public function deleteObservationsForUser(User $user)
    {
        $this->em->getRepository(Observation::class)->deleteByUser($user->getId());
    }

    /**
     * Get observations validate for user
     *
     * @param User $user
     * @return array
     */
    public function getObservationsValidate(User $user)
    {
        $obs = $this->em->getRepository('AppBundle:Observation')->findBy(array(
            'status' => Observation::VALIDATED,
            'user' => $user->getId()
        ));
        return $obs;
    }

    /**
     * @param Paginator $obs
     * @param $page
     * @return array
     */
    public function getPagination(Paginator $obs, $page)
    {

        $totalObs = $obs->count();
        $totalDisplayed = $obs->getIterator()->count();
        $maxPages = ceil($obs->count() / $this->list_limit);

        return ['totalObs' => $totalObs,
            'totalDisplayed' => $totalDisplayed,
            'current' => $page,
            'maxPages' => $maxPages,
            'totalItems' => count($obs)
        ];
    }

    public function createObservation(Observation $observation, Form $form, Request $request){

        $data = $form->getData();

        $user = $this->ts->getToken()->getUser();
        $observation->setUser($user);

        // User want to save observation as draft
        if ($form->get('save_draft')->isClicked()) {
            $observation->setStatus(Observation::DRAFT);
            $redirect = 'DRAFT';
        }

        // User want published observation,
        // only simple user need to have validation
        if ($form->get('save_published')->isClicked()) {

            if($user->getRole() == 'ROLE_OBSERVER'){
                $observation->setStatus(Observation::WAITING);
                $redirect = 'WAITING';
            }else{
                $observation->setStatus(Observation::VALIDATED);
                $observation->setNaturalist($user);
                $redirect = 'PUBLISHED';
            }
        }

        // Get date
        $observation->setWatched(\DateTime::createFromFormat('d/m/Y', $observation->getWatched()));

        // Get localisation
        $region     = explode(' - ', $observation->getPlace());
        $place      = $this->em->getRepository(FranceRegion::class)->FindOneBy(array('city' => trim($region[1])));
        $observation->setPlace($place->getCity());

        // If we can retrieve "True" coordinate
        // we take coordinate of the nearest city
        if(!is_null($data->getLatitude())) {
            $observation->setLatitude($data->getLatitude());
        }else{
            $observation->setLatitude($place->getLatitude());
        }

        if(!is_null($data->getLongitude())) {
            $observation->setLongitude($data->getLongitude());
        }else{
            $observation->setLongitude($place->getLongitude());
        }

        // Get taxref
        $taxref_name = $request->request->get('observation')['taxref'];
        $latin_name = substr($taxref_name, ($p = strpos($taxref_name, '(')+1), strrpos($taxref_name, ')')-$p);
        $taxref = $this->em->getRepository('AppBundle:Taxref')->findOneBy(array('taxon_sc' => $latin_name));

        $observation->setTaxref($taxref);

        if (!is_null($data->getImagePath())) {
            $file = $observation->getImagePath();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->observations_directory,
                $fileName
            );
            $observation->setImagePath($fileName);
        }else{
            $observation->setImagePath('default-image_observation.jpg');
        }

        $this->em->persist($observation);
        $this->em->flush();

        return $redirect;
    }


    public function validate(Observation $observation)
    {
        $naturalist = $this->ts->getToken()->getUser();
        $observation->setNaturalist($naturalist);
        $observation->setValidated(new \DateTime('now'));
        $observation->setStatus(Observation::VALIDATED);
        $this->em->persist($observation);
        $this->em->flush();

    }

    public function reject(Observation $observation, $data)
    {


        $naturalist = $this->ts->getToken()->getUser();
        $observation->setNaturalist($naturalist);
        $observation->setValidated(new \DateTime('now'));
        $observation->setStatus(Observation::REFUSED);
        $this->em->persist($observation);

        $notice = new Notification();
        $notice->setContent($data['reason']);
        $notice->setFromUser($naturalist);
        $notice->setToUser($observation->getUser());
        $this->em->persist($notice);

        if(isset($data['warn_admin']) && !empty($data['warn_admin'])) {
            $admins = $this->em->getRepository(User::class)->searchUsersByRole(1,'ROLE_ADMIN');
            foreach($admins as $admin) {
                $notice = new Notification();
                $notice->setContent($data['reason']);
                $notice->setFromUser($naturalist);
                $notice->setToUser($admin);
                $this->em->persist($notice);
            }
        }
        $this->em->flush();
    }
}

