<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Category;
use App\Entity\Drink;
use App\Entity\Product;
use App\Tests\DatabaseTestCase;

class DrinkTest extends DatabaseTestCase
{
    public function test_drink_record_can_be_created_in_database(): void
    {
        $drink = new Drink();
        $drink->setName('mohito');
        $drink->setDescription('test description');
        $drink->setPreparation('test preparation');
        $drink->setImage('test address');
        $user = $this->createUser();
        $drink->setAuthor($user);
        
        $this->entityManager->persist($drink);
        $this->entityManager->flush();
        $drinkRepo = $this->entityManager->getRepository(Drink::class);
        $drinkRecord = $drinkRepo->findOneBy(['name' => 'mohito']);

        $this->assertSame('mohito', $drinkRecord->getName());
        $this->assertSame('test description', $drinkRecord->getDescription());
        $this->assertSame('test preparation', $drinkRecord->getPreparation());
        $this->assertSame('test address', $drinkRecord->getImage());
        $this->assertSame($user, $drinkRecord->getAuthor());
    }

    public function test_category_can_be_added_and_received(): void
    {
        $drink = new Drink();
        $drink->setName('mohito');
        $drink->setDescription('test description');
        $drink->setPreparation('test preparation');
        $drink->setImage('test address');
        $drink->setAuthor($this->createUser());

        $category = new Category();
        $category->setName('słodki');

        $drink->addCategory($category);

        $this->entityManager->persist($drink);
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $drinkRepo = $this->entityManager->getRepository(Drink::class);
        $drinkRecord = $drinkRepo->findOneBy(['name' => 'mohito']);

        $this->assertEquals('słodki', $drinkRecord->getCategories()[0]->getName());
    }

    public function test_product_can_be_added_and_received(): void
    {
        $drink = new Drink();
        $drink->setName('mohito');
        $drink->setDescription('test description');
        $drink->setPreparation('test preparation');
        $drink->setImage('test address');
        $drink->setAuthor($this->createUser());

        $product= new Product();
        $product->setName('whiskey');

        $drink->addProduct($product);

        $this->entityManager->persist($drink);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $drinkRepo = $this->entityManager->getRepository(Drink::class);
        $drinkRecord = $drinkRepo->findOneBy(['name' => 'mohito']);

        $this->assertEquals('whiskey', $drinkRecord->getProducts()[0]->getName());
    }
}
