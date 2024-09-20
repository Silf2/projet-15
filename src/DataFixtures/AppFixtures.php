<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Album;
use App\Entity\Media;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    )
    {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Création de l'utilisateur admin
        $admin = new User();
        $admin->setName('Ina');
        $admin->setEmail('Ina@gmail.com');
        $admin->setDescription('');
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, "password"));
        $manager->persist($admin);

        $blocked = new User();
        $blocked->setName('Blocked');
        $blocked->setEmail('Blocked@gmail.com');
        $blocked->setDescription('');
        $blocked->setRoles(["ROLE_BLOCKED"]);
        $blocked->setPassword($this->userPasswordHasher->hashPassword($blocked, "password"));
        $manager->persist($blocked);

        // Création d'utilisateurs aléatoires
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setName($faker->name);
            $user->setEmail($faker->unique()->email);
            $user->setDescription($faker->sentence);
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
            $manager->persist($user);
            $users[] = $user;
        }

        // Création d'albums
        $albums = [];
        for ($i = 0; $i < 5; $i++) {
            $album = new Album();
            $album->setName($faker->word . ' Album');
            $manager->persist($album);
            $albums[] = $album;
        }

        // Création de médias (images)
        for ($i = 0; $i < 20; $i++) {
            $media = new Media();
            $media->setTitle($faker->sentence(3));
            $media->setPath($faker->imageUrl());
            $media->setUser($faker->randomElement($users));
            $media->setAlbum($faker->randomElement($albums));
            $manager->persist($media);
        }

        // Flush pour sauvegarder toutes les entités
        $manager->flush();
    }
}