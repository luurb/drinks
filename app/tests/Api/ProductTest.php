<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Product;

class ProductTest extends ApiTestCase
{
    public function testGetProductByName(): void
    {
        $product = new Product();
        $product->setName('whiskey');

        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        static::createClient()->request('GET', '/api/products/whiskey');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Product',
            '@type' => 'Product',
            'name' => 'whiskey'
        ]);
    }

    public function testPost(): void
    {
        static::createClient()->request('POST', '/api/products', [
            'json' => [
                'name' => 'whiskey',
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Product',
            '@type' => 'Product',

            'name' => 'whiskey',
        ]);
    }

    public function testPatchNotAllowed(): void
    {
        static::createclient()->request('PATCH', '/api/products');
        $this->assertresponsestatuscodesame(405);
    }

    public function testDeleteNotAllowed(): void
    {
        static::createclient()->request('DELETE', '/api/products');
        $this->assertresponsestatuscodesame(405);
    }
}
