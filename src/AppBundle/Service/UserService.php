<?php
/**
 * Created by PhpStorm.
 * User: psa
 * Date: 16/09/17
 * Time: 09:00
 */

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
    private $ts;
    private $list_limit;

    /**
     * UserService constructor.
     *
     * @param EntityManager $em
     * @param TokenStorage $ts
     *
     * @param $list_limit
     */
    public function __construct(TokenStorage $ts,$list_limit)
    {
        $this->ts = $ts;
        $this->list_limit = $list_limit;
    }


    /**
     * Get pagination parameters
     *
     * @param $users    UserService list
     * @param int $page Page to display
     *
     * @return array  Current pagination
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

