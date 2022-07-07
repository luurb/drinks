<?php

namespace App\Tests\Api;

use App\Entity\Product;

class ProductTest extends CustomApiTestCase
{
   private $client;
   private $entityManager;

   protected function setUp(): void
   {
      parent::setUp();
      $this->client = static::createClient();
      $this->entityManager = static::getContainer()->get('doctrine')->getManager();
   }

   public function createProduct(string $name): void
   {
      $product = new Product();
      $product->setName($name);
      $this->entityManager->persist($product);
      $this->entityManager->flush();
   }

   public function testGetProductByName(): void
   {
      $this->createProduct('whiskey');

      $this->client->request('GET', '/api/products/whiskey');
      $this->assertResponseIsSuccessful();
      $this->assertJsonContains([
         '@context' => '/api/contexts/Product',
         '@type' => 'Product',
         'name' => 'whiskey'
      ]);
   }

   public function testPost(): void
   {
      $this->client->request('POST', '/api/products', [
         'json' => [
            'name' => 'whiskey',
         ]
      ]);
      $this->assertResponseStatusCodeSame(401);
      $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

      $this->createUserAndLogIn($this->client, 'test', '12345');
      $this->client->request('POST', '/api/products', [
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

   public function testPut(): void
   {
      $this->createProduct('whiskey');

      //Anonymous user
      $this->client->request('PUT', '/api/products/whiskey', [
         'json' => [
            'name' => 'test'
         ]
      ]);
      $this->assertResponseStatusCodeSame(401);

      //Logged withour admin role
      $this->createUserAndLogIn($this->client, 'test', '12345');
      $this->client->request('PUT', '/api/products/whiskey', [
         'json' => [
            'name' => 'test'
         ]
      ]);
      $this->assertResponseStatusCodeSame(403);

      $this->createUser('admin', '12345', ['ROLE_ADMIN']);
      $this->logIn($this->client, 'admin', '12345');
      $this->client->request('PUT', '/api/products/whiskey', [
         'json' => [
            'name' => 'test'
         ]
      ]);
      $this->assertResponseIsSuccessful();
   }

   public function testPatch(): void
   {
      $this->createProduct('whiskey');

      //Logged withour admin role
      $this->createUserAndLogIn($this->client, 'test', '12345');
      $this->client->request('PATCH', '/api/products/whiskey', [
         'json' => [
            'name' => 'test'
         ],
         'headers' => [
            'content-type' => 'application/merge-patch+json'
         ]
      ]);
      $this->assertResponseStatusCodeSame(403);

      $this->createUser('admin', '12345', ['ROLE_ADMIN']);
      $this->logIn($this->client, 'admin', '12345');
      $this->client->request('PATCH', '/api/products/whiskey', [
         'json' => [
            'name' => 'test'
         ],
         'headers' => [
            'content-type' => 'application/merge-patch+json'
         ]
      ]);
      $this->assertResponseIsSuccessful();
   }

   public function testDelete(): void
   {
      $this->createProduct('whiskey');

      //Anonymous
      $this->client->request('DELETE', '/api/products/whiskey');
      $this->assertResponseStatusCodeSame(401);

      //Logged withour admin role
      $this->createUserAndLogIn($this->client, 'test', '12345');
      $this->client->request('DELETE', '/api/products/whiskey');
      $this->assertResponseStatusCodeSame(403);

      //Logged user with admin role
      $this->createUser('admin', '12345', ['ROLE_ADMIN']);
      $this->logIn($this->client, 'admin', '12345');
      $this->client->request('DELETE', '/api/products/whiskey');
      $this->assertResponseStatusCodeSame(204);
   }

   public function test_api_returns_products_filtered_by_name(): void
   {
      $names = ['sok z limonki', 'sok z cytryny', 'whiskey', 'syrop cukrowy'];

      foreach ($names as $name) {
         $product = new Product();
         $product->setName($name);
         $this->entityManager->persist($product);
      }

      $this->entityManager->flush();

      $this->client->request('GET', '/api/products?name=sok');

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

      $this->client->request('GET', '/api/products?name=whi');

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
