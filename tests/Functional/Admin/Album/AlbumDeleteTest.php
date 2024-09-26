<?php

namespace App\Tests\Functional\Admin\Album;

use App\Entity\Album;
use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class AlbumDeleteTest extends FunctionalTestCase
{
    public function testDeleteAlbum(): void
    {
        $album = new Album();
        $album->setName('Album à supprimer');

        $albumRepository = $this->getEntityManager()->getRepository(className: Album::class);
        $em = $this->getEntityManager();

        $em->persist($album);
        $em->flush();
        $id = $album->getId();

        $this->login();

        $this->assertNotNull($albumRepository->find($id));

        $this->client->request('GET', '/admin/album/delete/' . $album->getId());

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);        
        $crawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    
        $deletedAlbum = $albumRepository->find($id);
        $this->assertNull($deletedAlbum);
    }

    public function testDeleteNonExistingAlbum(): void
    {
        $this->login();

        $this->client->request('GET', '/admin/album/delete/-1');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}