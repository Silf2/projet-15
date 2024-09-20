<?php

namespace App\Tests\Functional\Homepages;

use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class GuestControllerTest extends FunctionalTestCase
{
    public function testGuestsPage(): void
    {
        $em = $this->getEntityManager();

        $this->client->request('GET', '/guests');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorExists('.guest');
    }
}
