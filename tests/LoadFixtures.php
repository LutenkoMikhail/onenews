<?php


namespace App\Tests;


use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoadFixtures extends WebTestCase
{
    /**
     * @param array  $filters
     * @param int    $status
     * @param int    $count
     * @param array  $contains
     *
     * @dataProvider listProvider
     */
    protected function loadFixtures(array $classNames, $omName = null, $registryName = 'doctrine', $purgeMode = null)
    {
        $loader = new ContainerAwareLoader(static::$container);
        foreach ($classNames as $className) {
            $loader->addFixture(new $className);
        }
        $executor = new ORMExecutor($this->em);
        $executor->execute($loader->getFixtures(), true);
    }
}