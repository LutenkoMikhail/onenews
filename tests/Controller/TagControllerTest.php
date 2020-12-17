<?php

namespace App\Tests\Controller;

use App\DataFixtures\NewsFixtures;
use App\DataFixtures\TagFixtures;
use App\Tests\LoadFixtures;

class TagControllerTest extends LoadFixtures
{

    public function testIndexTags()
    {
        $this->loadFixtures([
            TagFixtures::class,
            NewsFixtures::class,
        ]);

        $client = static::createClient();

        $client->request('GET', '/api/v1/tags');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
