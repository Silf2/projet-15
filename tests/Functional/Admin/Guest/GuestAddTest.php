<?php

namespace App\Tests\Functional\Admin\Guest;

use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class GuestAddTest extends FunctionalTestCase
{
    public function testAddAlbum(): void
    {
        $this->login();
        $crawler = $this->get('/admin/guest/add');

        $form = $crawler->selectButton('Ajouter')->form(self::getFormData());
        
        $this->client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);        
        
        $crawler = $this->client->followRedirect();
        self::assertResponseIsSuccessful();
    }

        /**
     * @param array<string, mixed> $overrideData
     * @return array<string, mixed>
     */
    public static function getFormData(array $overrideData = []): array
    {
        $uniqueSuffix = uniqid();
        return [
            'user[name]' => 'username_' . $uniqueSuffix,
            'user[description]' => 'Zoubididou',
            'user[email]' => 'user_' . $uniqueSuffix . '@email.com',
            'user[password]' => 'password'
        ] + $overrideData;
    }
}