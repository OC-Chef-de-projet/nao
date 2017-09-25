<?php
/**
 * Created by PhpStorm.
 * User: psa
 * Date: 16/09/17
 * Time: 09:00
 */

namespace AppBundle\Service;

use AppBundle\Entity\Post;

class PostService
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
                'text' => 'Gestion des articles'
            ],
        ];
        return $breadcrumb;
    }

    public function getEditBreadcrumb()
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
                'text' => 'Gestion des articles'
            ],
            [
                'href' => '#',
                'text' => 'Rédiger un article'
            ],

        ];
        return $breadcrumb;
    }


    public function getPostsTabs($post)
    {
        $tabs = [
            Post::DRAFT => [
                'status' => Post::DRAFT,
                'text' => 'Brouillons',
                'active' => 0,
                'href' => ''
            ],
            Post::PUBLISHED => [
                'status' => Post::PUBLISHED,
                'text' => 'Publiés',
                'active' => 0,
                'href' => ''
            ]
        ];
        $tabs[$post]['active'] = 1;
        return $tabs;
    }
}