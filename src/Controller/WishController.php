<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    #[Route('/wish/list', name: 'wish_affichageList')]
    public function affichageList(): Response
    {
        return $this->render('wish/list.html.twig');
    }

    #[Route('/wish/detail', name: 'wish_affichageDetail')]
    public function affichageDetail(): Response
    {
        return $this->render('wish/detail.html.twig');
    }
}
