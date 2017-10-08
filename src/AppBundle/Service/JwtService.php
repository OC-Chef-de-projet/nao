<?php

namespace AppBundle\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;


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
        $user  = $this->ts->getToken()->getUser();

        if (is_object($user) && $user instanceof User) {
            return $this->jwtManager->create($user);
        }

        return null;
    }
}
