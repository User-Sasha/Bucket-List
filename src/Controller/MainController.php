<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route(
        '/',
        name: 'main_index'
    )]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }


    #[Route(
        '/aboutUs',
        name: 'main_aboutus'
    )]
    public function AboutUs(): Response
    {
        return $this->render('main/aboutus.html.twig');
    }
}
