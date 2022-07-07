<?php

namespace App\Tests\Api;

use App\Entity\Product;

class ProductTest extends CustomApiTestCase
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
        $client = static::createClient();

        $client->request('POST', '/api/products', [
            'json' => [
                'name' => 'whiskey',
            ]
        ]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->createUserAndLogIn($client, 'test', '12345');

        $client->request('POST', '/api/products', [
            'json' => [
                'name' => 'whiskey',
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@type' => 'Product',
            'name' => 'whiskey'
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

    public function test_api_returns_products_filtered_by_name(): void
    {
        $names = ['sok z limonki', 'sok z cytryny', 'whiskey', 'syrop cukrowy'];
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        foreach ($names as $name) {
            $product = new Product();
            $product->setName($name);
            $entityManager->persist($product);
        }

        $entityManager->flush();

        static::createClient()->request('GET', '/api/products?name=sok');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Product',
            '@id' => '/api/products',
            '@type' => 'hydra:Collection',
            'hydra:member' => [
                [
                    '@type' => 'Product',
                    'name' => 'sok z limonki',
                ],
                [
                    '@type' => 'Product',
                    'name' => 'sok z cytryny',
                ],
            ],
            'hydra:totalItems' => 2,
        ]);

        static::createClient()->request('GET', '/api/products?name=whi');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Product',
            '@id' => '/api/products',
            '@type' => 'hydra:Collection',
            'hydra:member' => [
                [
                    '@type' => 'Product',
                    'name' => 'whiskey',
                ],
            ],
            'hydra:totalItems' => 1,
        ]);
    }
}
