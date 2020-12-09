<?php

namespace App\DataFixtures;

use App\Entity\News;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NewsFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i <= 3; $i++) {
            $news = new News();
            $news->setName('News number__' . $i);
            $news->setDescription('News description__' . $i);
            $news->setShortDescription('News short description__' . $i);
            $news->setCreatedAt(new DateTime('NOW'));
            $news->setUpdatedAt(new DateTime('NOW'));
            $news->setActive(true);
            $news->addTag($this->getReference(TagFixtures::TAG_REFERENCE
                . rand(1, TagFixtures::TAG_MAX)));
            $manager->persist($news);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(TagFixtures::class);
    }
}
