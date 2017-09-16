<?php
/**
 * Created by PhpStorm.
 * User: psa
 * Date: 16/09/17
 * Time: 09:00
 */

namespace AppBundle\Service;

class Admin
{

    public function getHomeContent()
    {
        $cards = [];
        $cards[] = [
            'title' => 'Gérer les utilisateurs',
            'image' => '',
            'content' => 'Gestion des utilisateurs',
            'link' => '#'
        ];
        $cards[] = [
            'title' => 'Gérer les articles',
            'image' => '',
            'content' => 'Gestion des articles du blog',
            'link' => '#'
        ];

        $cards[] = [
            'title' => 'Modérer les commentaires',
            'image' => '',
            'content' => 'Modérer les commentaires des articles du blog',
            'link' => '#'

        ];
        return $cards;
    }

    public function getHomeBreadcrumb()
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
        ];
        return $breadcrumb;
    }
}