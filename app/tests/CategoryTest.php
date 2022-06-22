<?php

namespace App\Tests;

use App\Entity\Category;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    /** @var EntityManager*/
    private $entityManager;

    protected function setUp(): void
    {
        $kernel =  self::bootKernel();
        DatabasePrimer::prime($kernel);
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

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
}
