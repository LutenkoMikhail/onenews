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
    protected $url = '/api/v1/tags';


    /**
     * @param int $statusOk
     * @param int $tagMax
     * @param string $requestMethod
     * @dataProvider IndexDataProvider
     */


    public function testIndexTags(int $statusOk, int $tagMax, string $requestMethod)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);
        $this->client->request($requestMethod, $this->url);

        $this->assertEquals($statusOk, $this->client->getResponse()->getStatusCode());
        $this->assertCount($tagMax, $this->getJsonResponse());
    }

    /**
     * @return array
     */
    public function indexDataProvider(): array
    {
        return [
            [
                Response::HTTP_OK,
                TagFixtures::TAG_MAX,
                Request::METHOD_GET
            ],
        ];
    }

    /**
     * @param ParamWrapper $id
     * @param int $statusOk
     * @param int $notFound
     * @param string $requestMethod
     * @param int $wrongEntry
     * @dataProvider showDataProvider
     */
    public function testShow(ParamWrapper $id, int $statusOk, int $notFound, string $requestMethod, int $wrongEntry)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);
        $this->processParamWrapper($id);

        $this->client->request($requestMethod, $this->url . '/' . $id);
        $this->assertEquals($statusOk, $this->client->getResponse()->getStatusCode());

        $this->client->request($requestMethod, $this->url . '/' . $wrongEntry);
        $this->assertEquals($notFound, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function showDataProvider(): array
    {
        return [
            [
                new ParamWrapper(Tag::class, ['name' => '1_TAG_1']),
                Response::HTTP_OK,
                Response::HTTP_NOT_FOUND,
                Request::METHOD_GET,
                'wrongEntry' => -1
            ],
        ];
    }

    /**
     * @param ParamWrapper $id
     * @param int $noContent
     * @param int $notFound
     * @param string $requestMethod
     * @param int $wrongEntry
     * @dataProvider deleteDataProvider
     */
    public function testDelete(ParamWrapper $id, int $noContent, int $notFound, string $requestMethod, int $wrongEntry)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);
        $this->processParamWrapper($id);

        $this->client->request($requestMethod, $this->url . '/' . $id);
        $this->assertEquals($noContent, $this->client->getResponse()->getStatusCode());

        $this->client->request($requestMethod, $this->url . '/' . $wrongEntry);
        $this->assertEquals($notFound, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function deleteDataProvider(): array
    {
        return [
            [
                new ParamWrapper(Tag::class, ['name' => '1_TAG_1']),
                Response::HTTP_NO_CONTENT,
                Response::HTTP_NOT_FOUND,
                Request::METHOD_DELETE,
                'wrongEntry' => -1
            ],
        ];
    }

    /**
     * @param string $newName
     * @param string $badName
     * @param int $created
     * @param int $noCreated
     * @param string $requestMethod
     * @dataProvider newDataProvider
     */
    public function testNewTag(string $newName, string $badName, int $created, int $noCreated, string $requestMethod)
    {
        $this->client->request($requestMethod, $this->url, [
            'name' => $newName
        ]);
        $this->assertEquals($created, $this->client->getResponse()->getStatusCode());

        $this->client->request($requestMethod, $this->url, [
            'name' => $badName
        ]);
        $this->assertEquals($noCreated, $this->client->getResponse()->getStatusCode());
    }


    /**
     * @return array
     */
    public function newDataProvider(): array
    {
        return [
            [
                'newName' => 'NEW__TAG',
                'badName' => '',
                Response::HTTP_CREATED,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                Request::METHOD_POST
            ],
        ];
    }

    /**
     * @param ParamWrapper $id
     * @param string $newName
     * @param string $badName
     * @param int $update
     * @param int $noUpdate
     * @param int $notFound
     * @param string $requestMethod
     * @param int $wrongEntry
     * @dataProvider updateDataProvider
     */

    public function testUpdateTag(ParamWrapper $id, string $newName, string $badName, int $update, int $noUpdate, int $notFound, string $requestMethod, int $wrongEntry)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);
        $this->processParamWrapper($id);

        $this->client->request($requestMethod, $this->url . '/' . $id, [
            'name' => $newName
        ]);
        $this->assertEquals($update, $this->client->getResponse()->getStatusCode());

        $this->client->request($requestMethod, $this->url . '/' . $id, [
            'name' => $badName
        ]);
        $this->assertEquals($noUpdate, $this->client->getResponse()->getStatusCode());

        $this->client->request($requestMethod, $this->url . '/' . $wrongEntry, [
            'name' => $newName
        ]);
        $this->assertEquals($notFound, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function updateDataProvider(): array
    {
        return [
            [
                new ParamWrapper(Tag::class, ['name' => '1_TAG_1']),
                'newName' => 'NEW__TAG',
                'badName' => '',
                Response::HTTP_OK,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                Response::HTTP_NOT_FOUND,
                Request::METHOD_PATCH,
                'wrongEntry' => -1
            ],
        ];
    }

}
