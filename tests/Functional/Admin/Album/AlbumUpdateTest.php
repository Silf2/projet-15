<?php

namespace App\Tests\Functional\Admin\Album;

use App\Entity\Album;
use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class AlbumUpdateTest extends FunctionalTestCase
{
    public function testUpdateAlbum(): void
    {
        $album = new Album();
        $album->setName('Album à modifier');

        $albumRepository = $this->getEntityManager()->getRepository(className: Album::class);
        $em = $this->getEntityManager();

        $em->persist($album);
        $em->flush();
        $id = $album->getId();

        $this->login();

        $this->assertNotNull($albumRepository->find($id));

        $crawler = $this->get('/admin/album/update/' . $album->getId());
        $form = $crawler->selectButton('Modifier')->form(['album[name]' => 'Album modifié']);
        $this->client->submit($form);

        $updatedAlbum = $albumRepository->find($id);

        $this->assertEquals('Album modifié', $updatedAlbum->getName());
        $this->assertResponseRedirects('/admin/album');
    }

    public function testUpdateNonExistingAlbum(): void
    {
        $this->login();

        $this->client->request('GET', '/admin/album/update/-1');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}