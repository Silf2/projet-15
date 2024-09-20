<?php

namespace App\Tests\Functional\Admin\User;

use App\Tests\Functional\FunctionalTestCase;

class LogoutUserTest extends FunctionalTestCase
{
    public function testLogout(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Connexion')->form([
            '_username' => 'Ina',
            '_password' => 'password',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/');
        $this->client->followRedirect();

        $this->client->request('GET', '/logout');

        $this->assertResponseRedirects('/'); 
    }
}