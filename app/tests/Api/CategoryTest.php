<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Category;

class CategoryTest extends ApiTestCase
{
    public function testGetCategoryByName(): void
    {
        $category = new Category();
        $category->setName('słodki');

        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $entityManager->persist($category);
        $entityManager->flush();

        static::createClient()->request('GET', '/api/categories/słodki');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Category',
            '@type' => 'Category',
            'name' => 'słodki'
        ]);
    }

    public function testGetCollectionReturnNotFound(): void
    {
        static::createClient()->request('GET', '/api/categories');
        $this->assertResponseStatusCodeSame(404);
    }

    public function testPostNotAllowed(): void
    {
        static::createclient()->request('POST', '/api/categories');
        $this->assertresponsestatuscodesame(405);
    }

    public function testPutNotAllowed(): void
    {
        static::createclient()->request('PUT', '/api/categories');
        $this->assertresponsestatuscodesame(405);
    }

    public function testPatchNotAllowed(): void
    {
        static::createclient()->request('PATCH', '/api/categories');
        $this->assertresponsestatuscodesame(405);
    }

    public function testDeleteNotAllowed(): void
    {
        static::createclient()->request('DELETE', '/api/categories');
        $this->assertresponsestatuscodesame(405);
    }
}
