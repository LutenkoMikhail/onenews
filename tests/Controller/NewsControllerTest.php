<?php


namespace App\Tests\Controller;


use App\DataFixtures\Test\NewsFixtures;
use App\DataFixtures\Test\TagFixtures;
use App\Entity\News;
use App\Entity\Tag;
use App\Tests\AbstractTestAction;
use App\Tests\ParamWrapper;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsControllerTest extends AbstractTestAction
{
    public function testIndexNews()
    {
        $this->loadFixtures([
            NewsFixtures::class,
        ]);
        $this->client->request(Request::METHOD_GET, '/api/v1/news');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertCount(NewsFixtures::NEWS_MAX, $this->getJsonResponse());
    }

    /**
     * @param ParamWrapper $id
     *
     * @dataProvider dataProvider
     */
    public function testShow(ParamWrapper $id)
    {
        $this->loadFixtures([
            NewsFixtures::class,
        ]);
        $this->processParamWrapper($id);

        $this->client->request(Request::METHOD_GET, '/api/v1/news/' . $id);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->client->request(Request::METHOD_GET, '/api/v1/news/' . -1);
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
            NewsFixtures::class,
        ]);
        $this->processParamWrapper($id);

        $this->client->request(Request::METHOD_DELETE, '/api/v1/news/' . $id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());

        $this->client->request(Request::METHOD_DELETE, '/api/v1/news/' . -1);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @param ParamWrapper $id
     *
     * @dataProvider dataProviderTag
     */
    public function testNewNews(ParamWrapper $id)
    {
        $this->loadFixtures([
            TagFixtures::class,
        ]);

        $this->processParamWrapper($id);

        $this->client->request(Request::METHOD_POST, '/api/v1/news', [
            'name' => 'NEW__name',
            'shortDescription' => 'NEW__shortDescription',
            'description' => 'NEW__description',
            'active' => 1,
            'tags' => [$id],
        ]);
        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $err = $this->client->request(Request::METHOD_POST, '/api/v1/news', [
            'name' => '',
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->client->getResponse()->getStatusCode());
    }


    /**
     * @param ParamWrapper $idNews
     * @param ParamWrapper $idTag
     * @dataProvider dataProviderNewsAndTag
     */
    public function testUpdateNews(ParamWrapper $idNews, ParamWrapper $idTag)
    {
        $this->loadFixtures([
            NewsFixtures::class,
        ]);
        $this->processParamWrapper($idNews);
        $this->processParamWrapper($idTag);

        $this->client->request(Request::METHOD_PATCH, '/api/v1/news/' . $idNews, [
            'name' => 'NEW name',
            'tags' => [$idTag],
        ]);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->client->request(Request::METHOD_PATCH, '/api/v1/news/' . $idNews, [
            'name' => '',
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->client->getResponse()->getStatusCode());

        $this->client->request(Request::METHOD_PATCH, '/api/v1/news/' . -1, [
            'name' => 'NEW name',
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
                new ParamWrapper(News::class, ['name' => 'News number__1'])
            ],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderTag(): array
    {
        return [
            [
                new ParamWrapper(Tag::class, ['name' => '1_TAG_1'])
            ],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderNewsAndTag(): array
    {
        return [
            [
                new ParamWrapper(News::class, ['name' => 'News number__3']),
                new ParamWrapper(Tag::class, ['name' => '2_TAG_2'])
            ],
        ];
    }
}