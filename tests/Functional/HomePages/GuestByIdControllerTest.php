<?php

namespace App\Tests\Functional\Homepages;

use App\Entity\User;
use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class GuestByIdControllerTest extends FunctionalTestCase
{
    public function testGuestsPage(): void
    {
        $userRepository = $this->getEntityManager()->getRepository(className: User::class);
        $user = $userRepository->findOneByEmail('Ina@gmail.com');

        $this->client->request('GET', '/guest/' . $user->getId());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testBlockNonExistingUser(): void
    {
        $this->client->request('GET', '/guest/-1');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}