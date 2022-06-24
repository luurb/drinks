<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void 
    {
        $categories = [
            'słodki',
            'kwaśny',
            'orzeźwiający',
            'lekki',
            'mocny',
        ];

        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $categoryEntities[] = $category;

            $manager->persist($category);
        }

        $manager->flush();
    }
}