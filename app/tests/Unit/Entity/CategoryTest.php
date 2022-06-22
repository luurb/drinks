<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Category;
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
}
