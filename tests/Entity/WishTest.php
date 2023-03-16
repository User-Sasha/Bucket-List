<?php

namespace App\Tests\Entity;

use App\Entity\Wish;
use PHPUnit\Framework\TestCase;

class WishTest extends TestCase
{
    public function testGettersEtSetters(): void
    {
        $wish = (new Wish())
            ->setAuthor('Moi')
            ->setDescription('Une description')
            ->setTitle('Un Souhait')
            ->setIsPublished(true);
        $this->assertSame ('Moi', $wish->getAuthor ());
        $this->assertNotNull($wish->getDateCreated());
        $this->assertSame('Une description', $wish->getDescription());
        $this->assertSame('Un Souhait', $wish->getTitle());
        $this->assertTrue($wish->isIsPublished());
        $maintenant = new \DateTime();
        $wish->setDateCreated($maintenant);
        $this->assertSame($maintenant, $wish->getDateCreated());
    }
}
