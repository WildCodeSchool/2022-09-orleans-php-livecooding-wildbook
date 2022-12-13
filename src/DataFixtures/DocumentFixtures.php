<?php

namespace App\DataFixtures;

use App\Entity\Document;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class DocumentFixtures extends Fixture
{
    public const DOCUMENT_NUMBER = 50;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < self::DOCUMENT_NUMBER; $i++) {
            $document = new Document();
            $document->setTitle($faker->sentence(3));
            $document->setDescription($faker->paragraphs(3, true));
            $document->setIsbn($faker->isbn13());

            $manager->persist($document);
        }

        $manager->flush();
    }
}
