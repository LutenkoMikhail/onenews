<?php

namespace App\DataFixtures\demo;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('1@1.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '123456789'
        ));
        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setEmail('2@2.com');
        $user->setRoles($user->getRoles());
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '123456789'
        ));
        $manager->persist($user);
        $manager->flush();


    }

    public static function getGroups(): array
    {
        return ['demo'];
    }
}

