<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/wish', name: 'wish', methods: 'GET')] // Prefixe
class WishController extends AbstractController
{
    #[Route(
        '/list',
        name: '_affichageList',
        methods: 'GET'
    )]
    public function affichageList(): Response
    {
        return $this->render('wish/list.html.twig');
    }

    #[Route(
        '/detail/{id}',
        name: '_affichageDetail',
        requirements: ['id'=>'\d+']
    )]
    public function affichageDetail($id): Response
    {
        return $this->render('wish/detail.html.twig',
        compact('id')
        );
    }
}
