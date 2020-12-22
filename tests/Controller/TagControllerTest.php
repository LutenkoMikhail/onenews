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
     * @dataProvider listDataProvider
     */
    public function testList(int $status, int $tagsMax)
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
    public function listDataProvider(): array
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
     * @dataProvider fetchDataProvider
     */

    public function testFetch($id, int $status)
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
    public function fetchDataProvider(): array
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
     * @param array $data
     * @param int $status
     * @dataProvider createDataProvider
     */
    public function testCreate(array $data, int $status)
    {
        $this->client->request(Request::METHOD_POST, $this->url, $data);
        $this->assertEquals($status, $this->client->getResponse()->getStatusCode());

    }

    /**
     * @return array
     */
    public function createDataProvider(): array
    {
        return [
            [
                [
                    'name' => 'NEW__TAG'
                ],
                Response::HTTP_CREATED
            ],
            [
                [
                    'name' => ''
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ]
        ];
    }

    /**
     * @param $id
     * @param array $data
     * @param int $status
     * @dataProvider updateDataProvider
     */

    public function  testUpdate($id, array $data, int $status)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);
        $this->processParamWrapper($id);

        $this->client->request(Request::METHOD_PATCH, $this->url . '/' . $id,$data);
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
                [
                    'name' => 'UPDATE__TAG'
                ],
                Response::HTTP_OK
            ],
            [
                new ParamWrapper(Tag::class, ['name' => '1_TAG_1']),
                [
                    'name' => ''
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                -1,
                [
                    'name' => 'UPDATE__TAG'
                ],
                Response::HTTP_NOT_FOUND
            ]
        ];
    }
}

