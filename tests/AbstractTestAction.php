<?php


namespace App\Tests;


use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;

class AbstractTestAction extends WebTestCase
{

    protected $em;

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

    /**
     * {@inheritdoc}
     */
    protected function getJsonResponse()
    {
        $content = $this->client->getResponse()->getContent();
        if ($content) {
            static::assertJson($content);
            $content = json_decode($content, true);
        }
        return $content;
    }

    /**
     * @param $class
     * @param null $criteria
     * @param bool $fail
     * @return mixed
     */
    protected function getObjectOf($class, $criteria = null, $fail = true)
    {
        $criteria = $criteria ?: $this->findOneBy;
        $object = $this->em
            ->getRepository($class)
            ->findOneBy($criteria);
        if (!$object && $fail) {
            static::fail('test object (' . $class . ') not found: ' . print_r($criteria, true));
        }
        return $object;
    }

    protected function processParamWrappers(array &$params)
    {
        array_walk_recursive($params, function (&$value) {
            $this->processParamWrapper($value);
        });
    }

    protected function processParamWrapper(&$value)
    {
        if (is_object($value) && $value instanceof ParamWrapper) {
            $criteria = $value->getCriteria();
            $this->processParamWrappers($criteria);
            $entity = $this->getObjectOf($value->getClass(), $criteria);
            $path = $value->getPath();
            $result = [];
            foreach ((array)$path as $key => $singlePath) {
                $result[$key] = PropertyAccess::createPropertyAccessorBuilder()
                    ->enableExceptionOnInvalidIndex()
                    ->getPropertyAccessor()
                    ->getValue($entity, $singlePath);
            }
            $value = count($result) > 1 ? $result : $result[0];
        }
    }

    protected function tearDown(): void
    {
        $this->em->getConnection()->rollBack();
        parent::tearDown();
    }
}
