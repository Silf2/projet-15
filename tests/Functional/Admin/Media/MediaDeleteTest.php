<?php

namespace App\Tests\Functional\Admin\Media;

use App\Entity\Media;
use App\Repository\MediaRepository;
use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class MediaDeleteTest extends FunctionalTestCase
{
    public function testDeleteMedia(){
        $this->loginNotAdmin();
        $crawler = $this->get('/admin/media/add');
    
        $form = $crawler->selectButton('Ajouter')->form(self::getFormData());
        
        $this->client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);        
        
        $crawler = $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        $media = $this->getContainer()->get(MediaRepository::class)->findOneBy([], ['id' => 'DESC']);

        // S'assurer que le média a bien été créé
        $this->assertNotNull($media, 'Media should be created');
        $mediaId = $media->getId();

        $crawler = $this->get('/admin/media/delete/' . $mediaId);
        $this->client->followRedirect();
        
        self::assertResponseIsSuccessful();
    }

    public function testDeleteNonExistingMedia(){
        $this->loginNotAdmin();

        $this->client->request('GET', '/admin/media/delete/-1');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteNonPropertyMedia(){
        $this->login();
        $crawler = $this->get('/admin/media/add');
    
        $form = $crawler->selectButton('Ajouter')->form(self::getFormData());
        
        $this->client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);        
        
        $crawler = $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        $media = $this->getContainer()->get(MediaRepository::class)->findOneBy([], ['id' => 'DESC']);

        $this->assertNotNull($media, 'Media should be created');
        $mediaId = $media->getId();

        $this->loginNotAdmin();

        $this->client->request('GET', '/admin/media/delete/' . $mediaId);

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
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