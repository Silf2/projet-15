<?php

namespace App\Tests\Functional\Admin\Media;

use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class MediaAddTest extends FunctionalTestCase
{
    public function testAddMedia(){
        $this->loginNotAdmin();
        $crawler = $this->get('/admin/media/add');
    
        $form = $crawler->selectButton('Ajouter')->form(self::getFormData());
        
        $this->client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);        
        
        $crawler = $this->client->followRedirect();
        self::assertResponseIsSuccessful();
    }

    public function testAddMediaAsAdmin(){
        $this->login();
        
        $crawler = $this->get('/admin/media/add');
    
        $form = $crawler->selectButton('Ajouter')->form(self::getFormData());
        $this->client->submit($form);
        
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);        
        
        $crawler = $this->client->followRedirect();
        self::assertResponseIsSuccessful();
    }

    public static function getFormData(array $overrideData = []): array
    {
        return [
            'media[file]' => self::createFakeImage(),
            'media[title]' => 'Titre de Test',
        ] + $overrideData;
    }

    private static function createFakeImage(): UploadedFile
    {
        $image = imagecreatetruecolor(100, 100);
        ob_start();
        imagepng($image);
        $imageContent = ob_get_clean();

        $tmpFilePath = tempnam(sys_get_temp_dir(), 'test_img');
        file_put_contents($tmpFilePath, $imageContent);

        return new UploadedFile(
            $tmpFilePath,
            'test_img.png',
            'image/png',
            null,
            true // Simule un vrai fichier uploadé
        );
    }
}