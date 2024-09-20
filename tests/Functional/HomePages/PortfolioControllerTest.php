<?php

namespace App\Tests\Functional\Homepages;
use App\Tests\Functional\FunctionalTestCase;

final class PortfolioControllerTest extends FunctionalTestCase
{
    public function testPortfolioWithSpecificAlbum()
    {
        // Récupérer un ID d'album depuis la base de données de tests
        $albumId = 1; // Modifiez ceci en fonction de vos fixtures
        
        // Envoyer une requête GET à la route du portfolio
        $this->client->request('GET', '/portfolio/' . $albumId);

        // Vérifiez que la réponse est 200 OK
        $this->assertResponseStatusCodeSame(200);
    }

    public function testPortfolioWithAllMedias()
    {

        $this->client->request('GET', '/portfolio');

        // Vérifiez que la réponse est 200 OK
        $this->assertResponseStatusCodeSame(200);
    }
}