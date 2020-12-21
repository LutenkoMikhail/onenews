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
     * @param int $statusOk
     * @param int $newsMax
     * @param string $requestMethod
     * @dataProvider IndexDataProvider
     */
    public function testIndexNews(int $statusOk, int $newsMax, string $requestMethod)
    {
        $this->loadFixtures([
            NewsFixtures::class,
        ]);
        $this->client->request($requestMethod, $this->url);

        $this->assertEquals($statusOk, $this->client->getResponse()->getStatusCode());
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
                NewsFixtures::NEWS_MAX,
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
            NewsFixtures::class,
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
                new ParamWrapper(News::class, ['name' => 'News number__1']),
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
            NewsFixtures::class,
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
                new ParamWrapper(News::class, ['name' => 'News number__1']),
                Response::HTTP_NO_CONTENT,
                Response::HTTP_NOT_FOUND,
                Request::METHOD_DELETE,
                'wrongEntry' => -1
            ],
        ];
    }

    /**
     * @param ParamWrapper $id
     * @param array $newNews
     * @param array $badNews
     * @param int $created
     * @param int $noCreated
     * @param string $requestMethod
     * @dataProvider newDataProvider
     */

    public function testNewNews(ParamWrapper $id, array $newNews, array $badNews, int $created, int $noCreated, string $requestMethod)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);

        $this->processParamWrapper($id);
        $newNews['tags'] = [$id];

        $this->client->request($requestMethod, $this->url, $newNews);
        $this->assertEquals($created, $this->client->getResponse()->getStatusCode());

        $err = $this->client->request($requestMethod, $this->url, $badNews);
        $this->assertEquals($noCreated, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function newDataProvider(): array
    {
        return [
            [
                new ParamWrapper(Tag::class, ['name' => '1_TAG_1']),
                'newNews' => [
                    'name' => 'NEW__name',
                    'shortDescription' => 'NEW__shortDescription',
                    'description' => 'NEW__description',
                    'active' => 1,
                    'tags' => 0
                ],
                'badNews' => [
                    'name' => ''
                ],
                Response::HTTP_CREATED,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                Request::METHOD_POST
            ],
        ];
    }

    /**
     * @param ParamWrapper $idNews
     * @param ParamWrapper $idTag
     * @param array $upDateNews
     * @param array $badNews
     * @param int $update
     * @param int $noUpdate
     * @param int $notFound
     * @param string $requestMethod
     * @param int $wrongEntry
     * @dataProvider updateDataProvider
     */

    public function testUpdateNews(ParamWrapper $idNews, ParamWrapper $idTag, array $upDateNews, array $badNews, int $update, int $noUpdate, int $notFound, string $requestMethod, int $wrongEntry)
    {
        $this->loadFixtures([
            NewsFixtures::class,
        ]);
        $this->processParamWrapper($idNews);
        $this->processParamWrapper($idTag);
        $upDateNews['tags'] = [$idTag];

        $this->client->request($requestMethod, $this->url . '/' . $idNews, $upDateNews);
        $this->assertEquals($update, $this->client->getResponse()->getStatusCode());

        $this->client->request($requestMethod, $this->url . '/' . $idNews, $badNews);
        $this->assertEquals($noUpdate, $this->client->getResponse()->getStatusCode());

        $this->client->request($requestMethod, $this->url . '/' . $wrongEntry, $upDateNews);
        $this->assertEquals($notFound, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function updateDataProvider(): array
    {
        return [
            [
                new ParamWrapper(News::class, ['name' => 'News number__3']),
                new ParamWrapper(Tag::class, ['name' => '2_TAG_2']),
                'upDateNews' => [
                    'name' => 'upDateNews',
                    'shortDescription' => 'upDateNews',
                    'description' => 'upDateNews',
                    'active' => 1,
                    'tags' => 0
                ],
                'badNews' => [
                    'name' => ''
                ],
                Response::HTTP_OK,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                Response::HTTP_NOT_FOUND,
                Request::METHOD_PATCH,
                'wrongEntry' => -1
            ],
        ];
    }
}
