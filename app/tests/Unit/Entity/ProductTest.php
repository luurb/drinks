<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Drink;
use App\Entity\Product;
use App\Tests\DatabaseTestCase;

class ProductTest extends DatabaseTestCase
{
    public function test_product_record_can_be_created_in_database(): void
    {
        $product = new Product();
        $product->setName('biały rum');

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $productRepo = $this->entityManager->getRepository(Product::class);
        $productRecord = $productRepo->findOneBy(['name' => 'biały rum']);

        $this->assertSame('biały rum', $productRecord->getName());
    }

    public function test_drink_can_be_added_and_received_by_product(): void 
    {
        $drink = new Drink();
        $drink->setName('mohito');
        $drink->setDescription('test description');
        $drink->setPreparation('test preparation');
        $drink->setImage('test address');

        $product = new Product();
        $product ->setName('słodki');

        $product ->addDrink($drink);

        $this->entityManager->persist($drink);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $productRepo = $this->entityManager->getRepository(Product::class);
        $productRecord = $productRepo->findOneBy(['name' => 'słodki']);

        $this->assertEquals('mohito', $productRecord->getDrinks()[0]->getName());
    }
}
