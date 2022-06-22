<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Category;
use App\Entity\Drink;
use App\Tests\DatabaseTestCase;

class CategoryTest extends DatabaseTestCase
{
    public function test_category_record_can_be_created_in_database(): void
    {
        $category = new Category();
        $category->setName('słodki');

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $categoryRepo = $this->entityManager->getRepository(Category::class);
        $categoryRecord = $categoryRepo->findOneBy(['name' => 'słodki']);

        //Assertions
        $this->assertSame('słodki', $categoryRecord->getName());
    }

    public function test_drink_can_be_added_and_received_by_category(): void 
    {
        $drink = new Drink();
        $drink->setName('mohito');
        $drink->setDescription('test description');
        $drink->setPreparation('test preparation');
        $drink->setImage('test address');

        $category = new Category();
        $category->setName('słodki');

        $category->addDrink($drink);

        $this->entityManager->persist($drink);
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $categoryRepo = $this->entityManager->getRepository(Category::class);
        $categoryRecord = $categoryRepo->findOneBy(['name' => 'słodki']);

        $this->assertEquals('mohito', $categoryRecord->getDrinks()[0]->getName());
    }
}
