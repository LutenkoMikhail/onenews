<?php

namespace App\DataFixtures;

use App\Entity\News;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NewsFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {

        // create 3 news!
        for ($i = 0; $i < 3; $i++) {
            $news = new News();
            $numberNews=$i+1;
            $news->setName('News number__'.$numberNews);
            $news->setDescription('News description__'.$numberNews);
            $news->setShortDescription('News short description__'.$numberNews);
            $news->setCreatedAt(new DateTime('NOW'));
            $news->setUpdatedAt(new DateTime('NOW'));
            $news->setActive(true);
//            $news->addTag($tags->getId());
            $manager->persist($news);
        }
        $manager->flush();
    }
}
