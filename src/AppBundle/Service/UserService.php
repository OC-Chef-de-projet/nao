<?php

namespace AppBundle\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;

/**
 * Class User
 *
 * @package AppBundle\Service
 */
class UserService
{
    private $em;
    private $ts;
    private $list_limit;

    /**
     * UserService constructor.
     *
     * @param TokenStorage $ts
     * @param $list_limit
     */
    public function __construct(EntityManager $em, TokenStorage $ts,$list_limit)
    {
        $this->em           = $em;
        $this->ts           = $ts;
        $this->list_limit   = $list_limit;
    }

    /**
     * Get pagination parameters
     *
     * @param Paginator $users
     * @param $page
     * @return array Current pagination
     */
    public function getPagination(Paginator $users,$page)
    {

        $totalUsers = $users->count();
        $totalDisplayed = $users->getIterator()->count();
        $maxPages = ceil($users->count() / $this->list_limit);

        return [
            'totalUsers' => $totalUsers,
            'totalDisplayed' => $totalDisplayed,
            'current' => $page,
            'maxPages' => (int)$maxPages,
        ];
    }

    /**
     * Get user informations
     *
     * @param $url
     * @return array
     */
    public function getUserInfo($url)
    {

        $response = [];
        $user = $this->ts->getToken()->getUser();

        if (!$user) {
            return [];
        }

        if ($user) {
            $image = $user->getImagePath();
            if($image) {
                $image = $url . '/images/users/' . $image;
            }

            $response = [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'aboutme' => $user->getAboutme(),
                'image' => $image,
                'created' => $user->getCreated(),
                'roleString' => $user->getRoleString(),
                'name' => $user->getName()
            ];
        }
        return $response;
    }
}

