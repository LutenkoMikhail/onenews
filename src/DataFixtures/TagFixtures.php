<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 5 tags!
        for ($i = 0; $i < 5; $i++) {
            $tag = new Tag();
            $numberTag=$i+1;
            $tag->setName($numberTag.'_TAG_'.$numberTag);
            $manager->persist($tag);
        }
        $manager->flush();
    }
}
