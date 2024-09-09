<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    )
    {}
    
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setName('Ina');
        $user->setEmail('Ina@gmail.com');
        $user->setDescription('');
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));

        $manager->persist($user);
        $manager->flush();
    }
}