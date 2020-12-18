<?php


namespace App\Tests;


use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractActionTest extends WebTestCase
{

    protected  $em;

    protected $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bootedKernel = static::bootKernel();
        $this->client = static::$container->get('test.client');
        $this->client->disableReboot();
        $this->em = static::$container->get('doctrine.orm.entity_manager');
        $this->em->getConnection()->beginTransaction();
    }

    protected function loadFixtures(array $classNames, $omName = null, $registryName = 'doctrine', $purgeMode = null)
    {
        $loader = new ContainerAwareLoader(static::$container);
        foreach ($classNames as $className) {
            $loader->addFixture(new $className);
        }
        $executor = new ORMExecutor($this->em);
        $executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown(): void
    {
        $this->em->getConnection()->rollBack();
        parent::tearDown();
    }
}
