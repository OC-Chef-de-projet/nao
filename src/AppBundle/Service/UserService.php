<?php
/**
 * Created by PhpStorm.
 * User: psa
 * Date: 16/09/17
 * Time: 09:00
 */

namespace AppBundle\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class User
 *
 * @package AppBundle\Service
 */
class UserService
{

    private $list_limit;

    public function __construct($list_limit)
    {
        $this->list_limit = $list_limit;
    }



    /**
     * Get user index breadcrumb
     *
     * @return array
     */
    public function getIndexBreadcrumb()
    {
        $breadcrumb = [
            [
                'href' => 'admin_homepage',
                'text' => 'Accueil'
            ],
            [
                'href' => 'admin_homepage',
                'text' => 'Administration'
            ],
            [
                'href' => 'admin_user_index',
                'text' => 'Gestion des utilisateurs'
            ],
        ];
        return $breadcrumb;
    }


    /**
     * Tabs form admin user index
     * @param $role
     * @return array
     */
    public function getUsersTabs($role)
    {
        $tabs = [
            'ROLE_OBSERVER' => [
                'role' => 'ROLE_OBSERVER',
                'text' => 'Particuliers',
                'active' => 0,
                'href' => ''
            ],
            'ROLE_NATURALIST' => [
                'role' => 'ROLE_NATURALIST',
                'text' => 'Naturalistes',
                'active' => 0,
                'href' => ''
            ],
            'ROLE_ADMIN' => [
                'role' => 'ROLE_ADMIN',
                'text' => 'Administrateurs',
                'active' => 0
            ],
        ];
        $tabs[$role]['active'] = 1;
        return $tabs;
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
            'maxPages' => $maxPages,
        ];
    }

}

