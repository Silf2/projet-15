<?php

namespace App\Tests\Functional\Homepages;

use App\Tests\Functional\FunctionalTestCase;

class AboutControllerTest extends FunctionalTestCase
{
    public function testAboutPage()
    {
        $crawler = $this->get('/about', ['GET']);

        $this->assertResponseIsSuccessful();
    }
}