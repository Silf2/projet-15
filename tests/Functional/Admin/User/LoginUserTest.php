<?php

namespace App\Tests\Functional\Admin\User;

use App\Tests\Functional\FunctionalTestCase;

class LoginUserTest extends FunctionalTestCase
{
    public function testLogin(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Connexion')->form([
            '_username' => 'Ina',
            '_password' => 'password',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/');
    }
}