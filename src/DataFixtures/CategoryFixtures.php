<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = [
        ['Livre', '#2288aa'],
        ['DVD', '#0088ff'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $key => $categoryData) {
            $category = new Category();
            $category->setName($categoryData[0]);
            $category->setColor($categoryData[1]);
            $this->addReference('category' . $key, $category);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
