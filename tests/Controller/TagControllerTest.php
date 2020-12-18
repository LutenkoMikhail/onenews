<?php

namespace App\Tests\Controller;

use App\DataFixtures\Test\TagFixtures;
use App\Entity\Tag;
use App\Tests\AbstractTestAction;
use App\Tests\ParamWrapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagControllerTest extends AbstractTestAction
{

    public function testIndexTags()
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);
        $this->client->request(Request::METHOD_GET, '/api/v1/tags');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertCount(TagFixtures::TAG_MAX, $this->getJsonResponse());
    }

    /**
     * @param ParamWrapper $id
     *
     * @dataProvider dataProvider
     */
    public function testShow(ParamWrapper $id)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);

        $this->processParamWrapper($id);

        $this->client->request(Request::METHOD_GET, '/api/v1/tags/' . $id);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->client->request(Request::METHOD_GET, '/api/v1/tags/' . -1);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @param ParamWrapper $id
     *
     * @dataProvider dataProvider
     */
    public function testDelete(ParamWrapper $id)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);

        $this->processParamWrapper($id);
        $this->client->request(Request::METHOD_DELETE, '/api/v1/tags/' . $id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());

        $this->client->request(Request::METHOD_DELETE, '/api/v1/tags/' . -1);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }


    public function testNewTag()
    {
        $this->client->request(Request::METHOD_POST, '/api/v1/tags', [
            'name' => 'NEW__TAG'
        ]);
        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $this->client->request(Request::METHOD_POST, '/api/v1/tags', [
            'name' => ''
        ]);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @param ParamWrapper $id
     *
     * @dataProvider dataProvider
     */
    public function testUpdateTag(ParamWrapper $id)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);
        $this->processParamWrapper($id);

        $this->client->request(Request::METHOD_PATCH, '/api/v1/tags/' . $id, [
            'name' => 'NEW__TAG'
        ]);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->client->request(Request::METHOD_PATCH, '/api/v1/tags/' . $id, [
            'name' => ''
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->client->getResponse()->getStatusCode());

        $this->client->request(Request::METHOD_PATCH, '/api/v1/tags/' . -1, [
            'name' => 'NEW__TAG'
        ]);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                new ParamWrapper(Tag::class, ['name' => '1_TAG_1'])
            ],
        ];
    }

}
