<?php

namespace App\Tests\Functional\Admin\Guest;

use App\Entity\User;
use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class GuestBlockTest extends FunctionalTestCase
{
    public function testBlockUser(): void
    {
        $this->login();

        $user = $this->createUser(['ROLE_USER']);

        $userRepository = $this->getEntityManager()->getRepository(className: User::class);
        $em = $this->getEntityManager();

        $em->persist($user);
        $em->flush();
        $id = $user->getId();

        $this->client->request('GET', '/admin/guest/block/' . $id);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND); 

        $this->client->followRedirect(); 

        $updatedUser = $userRepository->find($user->getId());
        $this->assertContains('ROLE_BLOCKED', $updatedUser->getRoles());
    }

    public function testUnblockUser(): void
    {
        $this->login();

        $user = $this->createUser(['ROLE_BLOCKED']);

        $userRepository = $this->getEntityManager()->getRepository(className: User::class);
        $em = $this->getEntityManager();

        $em->persist($user);
        $em->flush();
        $id = $user->getId();

        $this->client->request('GET', '/admin/guest/block/' . $id);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND); 

        $this->client->followRedirect(); 

        $updatedUser = $userRepository->find($user->getId());
        $this->assertContains('ROLE_USER', $updatedUser->getRoles());
    }

    public function testBlockNonExistingUser(): void
    {
        $this->login();

        $this->client->request('GET', '/admin/guest/block/-1'); // ID qui n'existe pas

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    private function createUser(array $role): User{
        $uniqueSuffix = uniqid();

        $user = new User();
        $user->setName('user_' . $uniqueSuffix);
        $user->setEmail('user_' . $uniqueSuffix . '@email.fr');
        $user->setPassword('password');
        $user->setRoles($role);

        return $user;
    }
}