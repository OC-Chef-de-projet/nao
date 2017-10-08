<?php

namespace AppBundle\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;


/**
 * Class JwtService
 * @package AppBundle\Service
 */
class JwtService{
    private $ts;

    /**
     * JwtService constructor.
     * @param TokenStorage $ts
     */
    public function __construct(TokenStorage $ts, $jwtManager){
        $this->ts           = $ts;
        $this->jwtManager   = $jwtManager;
    }

    /**
     * Create token acces
     * @return mixed
     */
    public function getToken(){
        $client  = $this->ts->getToken()->getUser();
        return $this->jwtManager->create($client);
    }
}