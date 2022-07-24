<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Drink;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Rating;
use App\Entity\Review;
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
        $categoryEntities = [];
        $productEntities = [];
        $userEntities = [];

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

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail('test' . $i . '@test.com');
            $user->setUsername('test' . $i);
            $user->setPassword('$2y$13$OVjSLJPRFDxViv9Vvy2R3uoALdDapd6.Z6K8Po9k.DrJee8Ss7eD6');

            $userEntities[] = $user;

            $manager->persist($user);
        }

        for ($i = 0; $i < 20; $i++) {
            $drink = new Drink();
            $drink->setName($faker->name());
            $drink->setDescription($faker->sentence(150));
            $preparation = '***' . $faker->sentence(20);
            $preparation .= '***' . $faker->sentence(20);
            $preparation .= '***' . $faker->sentence(20);
            $drink->setPreparation($preparation);
            $drink->setImage('http://localhost:8006/images/drinks/mojito.jpg');
            $drink->setAuthor($userEntities[rand(0, count($userEntities) - 1)]);
            $drink->addCategory($categoryEntities[rand(0, count($categoryEntities) - 1)]);
            $drink->addProduct($productEntities[rand(0, count($productEntities) - 1)]);
            $drink->addProduct($productEntities[rand(0, count($productEntities) - 1)]);
            $drink->addProduct($productEntities[rand(0, count($productEntities) - 1)]);
            $drink->addProduct($productEntities[rand(0, count($productEntities) - 1)]);

            for ($j = 0; $j < 5; $j++) {
                $rating = new Rating();
                $rating->setRating(rand(1, 5));
                $rating->setDrink($drink);
                $rating->setUser($userEntities[$j]);

                $manager->persist($rating);
            }

            $review = new Review();
            $review->setReview($faker->sentence(10));
            $review->setTitle($faker->word);
            $review->setDrink($drink);

            $review1 = new Review();
            $review1->setReview($faker->sentence(10));
            $review1->setTitle($faker->word);
            $review1->setDrink($drink);

            $firstUser = rand(0, count($userEntities) - 1);
            if ($firstUser == 0) {
                $secondUser = $firstUser + 1;
            } else if ($firstUser == 4) {
                $secondUser = $firstUser - 1;
            } else {
                $secondUser = $firstUser + 1;
            }

            $review->setAuthor($userEntities[$firstUser]);
            $review1->setAuthor($userEntities[$secondUser]);

            $manager->persist($review);
            $manager->persist($review1);
            $manager->persist($drink);
        }

        $manager->flush();
    }
}
