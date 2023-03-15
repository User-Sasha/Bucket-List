<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainTest extends WebTestCase
{
    public function testPageHome(): void
    {
        $client = static::createClient();
        // Je me rend sur l'url "/"
        $crawler = $client->request('GET', '/');
        // Je vérifie que l'url existe
        $this->assertResponseIsSuccessful();
        // Je vérifie qu'il y a bien une et une seule balise <nav>
        $this->assertSelectorExists('nav');
        // Je vérifie que dans cette barre de nav, j'ai un seul lien
        $this->assertCount(3, $crawler->filter('nav a'));
        // Je vérifie que la balise <body> contient le mot "Lorem"
        $this->assertSelectorTextContains('body', 'Lorem');
    }
}
