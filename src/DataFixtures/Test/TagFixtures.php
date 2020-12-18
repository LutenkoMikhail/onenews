<?php

namespace App\DataFixtures\Test;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public const TAG_REFERENCE = 'tag_alias_';
    public const TAG_MAX = 5;

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= self::TAG_MAX; $i++) {
            $tag = new Tag();
            $tag->setName($i.'_TAG_'.$i);
            $this->addReference(self::TAG_REFERENCE.$i, $tag);
            $manager->persist($tag);
        }
        $manager->flush();
    }
}
