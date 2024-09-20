<?php

namespace App\Tests\Functional\Admin\Album;

use App\Entity\Album;
use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class AlbumAddTest extends FunctionalTestCase
{
    public function testAddAlbum(): void
    {
        $this->login();
        $crawler = $this->get('/admin/album/add');

        $form = $crawler->selectButton('Ajouter')->form(['album[name]' => 'Super Album']);
        
        $this->client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);        
        
        $crawler = $this->client->followRedirect();
        self::assertResponseIsSuccessful();
    }
}