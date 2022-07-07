<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Drink;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
       return ['drinks'];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $products = [
            'biały rum',
            'wódka',
            'szampan',
            'wino czerwone',
            'wino białe',
            'piwo',
            'whiskey',
            'rum',
            'sok jabłkowy',
            'sok wiśniowy',
            'sok bananowy',
            'sok ananasowy',
            'sok grejpfrutowy',
            'syrop cukrowy',
            'lód',
            'mięta',
            'cukier',
            'kawa',
        ];

        $categories = [
            'słodki',
            'kwaśny',
            'orzeźwiający',
            'lekki',
            'mocny',
        ];

        $categoryEntities = [];
        $productEntities = [];

        foreach ($products as $productName) {
            $product = new Product();
            $product->setName($productName);
            $productEntities[] = $product;
            
            $manager->persist($product);
        }

        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $categoryEntities[] = $category;

            $manager->persist($category);
        }

        $user = new User();
        $user->setEmail('fdsfds@test.com');
        $user->setUsername('testfdfd');
        $user->setPassword('$2y$13$OVjSLJPRFDxViv9Vvy2R3uoALdDapd6.Z6K8Po9k.DrJee8Ss7eD6');
        $manager->persist($user);
        $manager->flush();

        for ($i = 0; $i < 10; $i++) {
            $drink = new Drink();
            $drink->setName($faker->word);
            $drink->setDescription($faker->sentence(40));
            $drink->setPreparation($faker->sentence(40));
            $drink->setImage('../images/drinks/mojito.jpg');
            $drink->setAuthor($user);
            $drink->addCategory($categoryEntities[rand(0, count($categoryEntities) - 1)]);
            $drink->addProduct($productEntities[rand(0, count($productEntities) - 1)]);
            $drink->addProduct($productEntities[rand(0, count($productEntities) - 1)]);
            $drink->addProduct($productEntities[rand(0, count($productEntities) - 1)]);
            $drink->addProduct($productEntities[rand(0, count($productEntities) - 1)]);

            $manager->persist($drink);
        }

        $manager->flush();
    }
}
