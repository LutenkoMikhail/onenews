<?php


namespace App\Tests\Controller;


use App\DataFixtures\Test\NewsFixtures;
use App\DataFixtures\Test\TagFixtures;
use App\Entity\News;
use App\Entity\Tag;
use App\Tests\AbstractTestAction;
use App\Tests\ParamWrapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsControllerTest extends AbstractTestAction

{


    protected $url = '/api/v1/news';

    /**
     * @param int $status
     * @param int $newsMax
     * @dataProvider IndexDataProvider
     */
    public function testIndexNews(int $status, int $newsMax)
    {
        $this->loadFixtures([
            NewsFixtures::class,
        ]);
        $this->client->request(Request::METHOD_GET, $this->url);

        $this->assertEquals($status, $this->client->getResponse()->getStatusCode());
        $this->assertCount($newsMax, $this->getJsonResponse());
    }


    /**
     * @return array
     */
    public function indexDataProvider(): array
    {
        return [
            [
                Response::HTTP_OK,
                NewsFixtures::NEWS_MAX
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
            NewsFixtures::class,
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
                new ParamWrapper(News::class, ['name' => 'News number__1']),
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
            NewsFixtures::class,
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
                new ParamWrapper(News::class, ['name' => 'News number__1']),
                Response::HTTP_NO_CONTENT
            ],
            [
                -1,
                Response::HTTP_NOT_FOUND
            ]
        ];
    }

    /**
     * @param array $newNews
     * @param int $status
     * @dataProvider newDataProvider
     */

    public function testNewNews(array $newNews, int $status)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);

        $this->processParamWrapper($newNews['tags']['id']);
        $this->client->request(Request::METHOD_POST, $this->url, $newNews);
        $this->assertEquals($status, $this->client->getResponse()->getStatusCode());

    }

    /**
     * @return array
     */
    public function newDataProvider(): array
    {
        return [
            [
                'newNews' => [
                    'name' => 'NEW__name',
                    'shortDescription' => 'NEW__shortDescription',
                    'description' => 'NEW__description',
                    'active' => 1,
                    'tags' => [
                        'id' => new ParamWrapper(Tag::class, ['name' => '1_TAG_1'])
                    ]
                ],
                Response::HTTP_CREATED
            ],
            [
                'newNews' => [
                    'name' => '',
                    'shortDescription' => '',
                    'description' => '',
                    'active' => 1,
                    'tags' => [
                        'id' => new ParamWrapper(Tag::class, ['name' => '1_TAG_1'])
                    ]
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
        ];
    }

    /**
     * @param  $id
     * @param array $updateNews
     * @param int $status
     * @dataProvider updateDataProvider
     */

    public function testUpdateNews($id, array $updateNews, int $status)
    {
        $this->loadFixtures([
            NewsFixtures::class,
        ]);
        $this->processParamWrapper($id);
        $this->processParamWrapper($updateNews['tags']['id']);

        $this->client->request(Request::METHOD_PATCH, $this->url . '/' . $id, $updateNews);
        $this->assertEquals($status, $this->client->getResponse()->getStatusCode());

    }

    /**
     * @return array
     */
    public function updateDataProvider(): array
    {
        return [
            [
                new ParamWrapper(News::class, ['name' => 'News number__3']),
                'updateNews' => [
                    'name' => 'updateNews',
                    'shortDescription' => 'updateNews',
                    'description' => 'updateNews',
                    'active' => 1,
                    'tags' => [
                        'id' => new ParamWrapper(Tag::class, ['name' => '2_TAG_2'])
                    ]
                ],
                Response::HTTP_OK
            ],
            [
                new ParamWrapper(News::class, ['name' => 'News number__3']),
                'updateNews' => [
                    'name' => '',
                    'shortDescription' => '',
                    'description' => '',
                    'active' => 1,
                    'tags' => [
                        'id' => new ParamWrapper(Tag::class, ['name' => '2_TAG_2'])
                    ]
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                -1,
                'updateNews' => [
                    'name' => 'updateNews',
                    'shortDescription' => 'updateNews',
                    'description' => 'updateNews',
                    'active' => 1,
                    'tags' => [
                        'id' => new ParamWrapper(Tag::class, ['name' => '2_TAG_2'])
                    ]
                ],
                Response::HTTP_NOT_FOUND
            ]
        ];
    }
}

