<?php

namespace App\DataFixtures;

use App\Entity\Document;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Filesystem\Filesystem;

class DocumentFixtures extends Fixture implements DependentFixtureInterface
{
    public const DOCUMENT_NUMBER = 20;

    public function __construct(private Filesystem $filesystem)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $this->filesystem->remove(__DIR__ . '/../../public/uploads/documents/');
        $this->filesystem->mkdir(__DIR__ . '/../../public/uploads/documents/');

        for ($i = 0; $i < self::DOCUMENT_NUMBER; $i++) {
            $document = new Document();
            $document->setTitle($faker->sentence(3));
            $document->setDescription($faker->paragraphs(3, true));
            $document->setIsbn($faker->isbn13());
            $imageName = uniqid() . '.png';
            copy('https://loremflickr.com/320/240/book', __DIR__ . '/../../public/uploads/documents/' . $imageName);
            $document->setImageName($imageName);
            $category = $this->getReference('category' . rand(0, count(CategoryFixtures::CATEGORIES) - 1));
            $document->setCategory($category);

            $manager->persist($document);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
