<?php

namespace App\Tests\Functional\Homepages;

use App\Tests\Functional\FunctionalTestCase;

class HomeControllerTest extends FunctionalTestCase
{
    public function testAboutPage()
    {
        $crawler = $this->get('/', ['GET']);

        $this->assertResponseIsSuccessful();
    }
}