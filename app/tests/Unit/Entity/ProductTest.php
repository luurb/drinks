<?php

namespace App\Tests\Unit\Entity;

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
}
