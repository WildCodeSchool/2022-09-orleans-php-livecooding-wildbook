<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Document;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AuthorFixtures extends Fixture
{
    public const AUTHOR_NUMBER = 100;

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < self::AUTHOR_NUMBER; $i++) {
            $author = new Author();
            $author->setFirstName($faker->firstName());
            $author->setLastName($faker->lastName());
            $author->setNationality($faker->countryCode());
            $this->addReference('author' . $i, $author);
            $manager->persist($author);
        }

        $manager->flush();
    }
}
