<?php

namespace App\Tests\Controller;

use App\DataFixtures\Test\TagFixtures;
use App\Tests\AbstractActionTest;

class TagControllerTest extends AbstractActionTest
{

    public function testIndexTags()
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);
        $this->client->request('GET', '/api/v1/tags');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }


}
