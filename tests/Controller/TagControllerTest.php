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
     * @param int $status
     * @param int $tagsMax
     * @dataProvider IndexDataProvider
     */
    public function testIndexTags(int $status, int $tagsMax)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);
        $this->client->request(Request::METHOD_GET, $this->url);

        $this->assertEquals($status, $this->client->getResponse()->getStatusCode());
        $this->assertCount($tagsMax, $this->getJsonResponse());
    }

    /**
     * @return array
     */
    public function indexDataProvider(): array
    {
        return [
            [
                Response::HTTP_OK,
                TagFixtures::TAG_MAX
            ]
        ];
    }

    /**
     * @param  $id
     * @param int $status
     * @dataProvider showDataProvider
     */

    public function testShow($id, int $status)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);

        $this->processParamWrapper($id);

        $this->client->request(Request::METHOD_GET, $this->url . '/' . $id);
        $this->assertEquals($status, $this->client->getResponse()->getStatusCode());

    }

    /**
     * @return array
     */
    public function showDataProvider(): array
    {
        return [
            [
                new ParamWrapper(Tag::class, ['name' => '1_TAG_1']),
                Response::HTTP_OK
            ],
            [
                -1,
                Response::HTTP_NOT_FOUND
            ]
        ];
    }


    /**
     * @param  $id
     * @param int $status
     * @dataProvider deleteDataProvider
     */
    public function testDelete($id, int $status)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);
        $this->processParamWrapper($id);

        $this->client->request(Request::METHOD_DELETE, $this->url . '/' . $id);
        $this->assertEquals($status, $this->client->getResponse()->getStatusCode());

    }

    /**
     * @return array
     */
    public function deleteDataProvider(): array
    {
        return [
            [
                new ParamWrapper(Tag::class, ['name' => '1_TAG_1']),
                Response::HTTP_NO_CONTENT
            ],
            [
                -1,
                Response::HTTP_NOT_FOUND
            ]
        ];
    }

    /**
     * @param string $newName
     * @param int $status
     * @dataProvider newDataProvider
     */
    public function testNewTag(string $newName, int $status)
    {
        $this->client->request(Request::METHOD_POST, $this->url, [
            'name' => $newName
        ]);
        $this->assertEquals($status, $this->client->getResponse()->getStatusCode());

    }

    /**
     * @return array
     */
    public function newDataProvider(): array
    {
        return [
            [
                'newName' => 'NEW__TAG',
                Response::HTTP_CREATED
            ],
            [
                'newName' => '',
                Response::HTTP_UNPROCESSABLE_ENTITY
            ]
        ];
    }

    /**
     * @param $id
     * @param string $newName
     * @param int $status
     * @dataProvider updateDataProvider
     */

    public function testUpdateTag($id, string $newName, int $status)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);
        $this->processParamWrapper($id);

        $this->client->request(Request::METHOD_PATCH, $this->url . '/' . $id, [
            'name' => $newName
        ]);
        $this->assertEquals($status, $this->client->getResponse()->getStatusCode());

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
                Response::HTTP_OK
            ],
            [
                new ParamWrapper(Tag::class, ['name' => '1_TAG_1']),
                'newName' => '',
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                -1,
                'newName' => 'NEW__TAG',
                Response::HTTP_NOT_FOUND
            ]
        ];
    }

}

