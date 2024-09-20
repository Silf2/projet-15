<?php

namespace App\Tests\Functional\Homepages;

use App\Entity\User;
use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class BlockedControllerTest extends FunctionalTestCase
{
    public function testBlockedPageAccess()
    {
        $userRepository = $this->getEntityManager()->getRepository(User::class);

        $blockedUser = $userRepository->findOneByEmail('Blocked@gmail.com');

        $this->client->loginUser($blockedUser);
        $this->client->request('GET', '/blocked');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h2', 'Vous êtes bloqué.');
    }

    public function testNonBlockedUserCannotAccessBlockedPage()
    {
        $userRepository = $this->getEntityManager()->getRepository(User::class);

        $nonBlockedUser = $userRepository->findOneByEmail('Ina@gmail.com'); // Admin ou un utilisateur normal

        $this->client->loginUser($nonBlockedUser);
        $this->client->request('GET', '/blocked');

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}