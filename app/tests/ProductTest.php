<?php

namespace App\Tests;

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductTest extends KernelTestCase
{
    /** @var EntityManager*/
    private $entityManager;

    protected function setUp(): void
    {
        $kernel =  self::bootKernel();
        DatabasePrimer::prime($kernel);
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

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
