<?php

namespace App\Tests\Functional\Admin\Album;

use App\Entity\User;
use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class GuestDeleteTest extends FunctionalTestCase
{
    public function testDeleteAlbum(): void
    {
        $user = $this->createUser();

        $userRepository = $this->getEntityManager()->getRepository(className: User::class);
        $em = $this->getEntityManager();

        $em->persist($user);
        $em->flush();
        $id = $user->getId();

        $this->login();

        $this->assertNotNull($userRepository->find($id));

        $this->client->request('GET', '/admin/guest/delete/' . $user->getId());

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);        
        $crawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    
        $deletedUser = $userRepository->find($id);
        $this->assertNull($deletedUser);
    }

    public function testDeleteNonExistingUser(): void
    {
        $this->login();

        $this->client->request('GET', '/admin/guest/delete/-1'); // ID qui n'existe pas

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    private function createUser(): User{
        $uniqueSuffix = uniqid();

        $user = new User();
        $user->setName('user_' . $uniqueSuffix);
        $user->setEmail('user_' . $uniqueSuffix . '@email.fr');
        $user->setPassword('password');

        return $user;
    }
}