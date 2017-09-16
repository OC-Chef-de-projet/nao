<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $cards = [];
        $cards[] = [
                'title' => 'Gérer les utilisateurs',
                'image' => '',
                'content' => 'Gestion des utilisateurs',
                'link' => '',
                'image' => '<div class="circle">&nbsp;</div>'

        ];
        $cards[] = [
            'title' => 'Gérer les articles',
            'image' => '',
            'content' => 'Gestion des articles du blog',
            'link' => '',
            'image' => '<div class="circle">&nbsp;</div>'

        ];

        $cards[] = [
            'title' => 'Modérer les commentaires',
            'image' => '',
            'content' => 'Modérer les comentaires des articles du blog',
            'link' => '',
            'image' => '<div class="circle">&nbsp;</div>'

        ];
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
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'cards' => $cards,
            'breadcrumb' => $breadcrumb
        ]);
    }
}
