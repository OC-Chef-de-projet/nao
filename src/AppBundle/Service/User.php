<?php
/**
 * Created by PhpStorm.
 * User: psa
 * Date: 16/09/17
 * Time: 09:00
 */

namespace AppBundle\Service;

class User
{

    public function getIndexBreadcrumb()
    {
        $breadcrumb = [
            [
                'href' => '#',
                'text' => 'Accueil'
            ],
            [
                'href' => '#',
                'text' => 'Administration'
            ],
            [
                'href' => '#',
                'text' => 'Gestion des utilisateurs'
            ],
        ];
        return $breadcrumb;
    }


    public function getUsersTabs($role,$page)
    {
        $tabs = [
            'ROLE_OBSERVER' => [
                'role' => 'ROLE_OBSERVER',
                'text' => 'Particuliers',
                'active' => 0
            ],
            'ROLE_NATURALIST' => [
                'role' => 'ROLE_NATURALIST',
                'text' => 'Naturalistes',
                'active' => 0
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
}