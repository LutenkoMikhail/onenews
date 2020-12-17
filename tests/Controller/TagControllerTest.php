<?php

namespace App\Tests\Controller;

use App\DataFixtures\NewsFixtures;
use App\DataFixtures\TagFixtures;
use App\Tests\FixtureAwareTestCase;

class TagControllerTest extends FixtureAwareTestCase
{
    protected $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->bootedKernel = static::bootKernel();
        $this->client = static::$container->get('test.client');
        $this->client->disableReboot();

        $this->addFixture(new TagFixtures());
        $this->addFixture(new NewsFixtures());
        $this->executeFixtures();
    }

    public function testIndexTags()
    {

        $this->client->request('GET', '/api/v1/tags');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

}
