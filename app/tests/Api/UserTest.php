<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\ProductFixtures;
use App\Entity\Drink;
use App\Entity\User;
use Faker\Factory;

class UserTest extends ApiTestCase
{
   private $client;
   private $entityManager;

   protected function setUp(): void
   {
      parent::setUp();
      $this->client = static::createClient();
      $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
   }

   public function testPost(): void
   {
      $response = $this->client->request('POST', '/api/users', [
         'json' => [
            'username' => 'test',
            'email' => 'test@example.com',
            'password' => 'test1234'
         ]
      ]);

      $this->assertResponseStatusCodeSame(201);
      $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
      $this->assertJsonContains([
         '@context' => '/api/contexts/User',
         '@type' => 'User',
         'username' => 'test',
         'email' => 'test@example.com'
      ]);
      $this->assertNotContains([
         'password' => 'test1234'
      ], json_decode($response->getContent(), true));
   }

   public function testRetrieveUser(): void
   {
      $this->client->request('POST', '/api/users', [
         'json' => [
            'username' => 'test',
            'email' => 'test@example.com',
            'password' => 'test1234'
         ]
      ]);

      $userRecord = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'test']);
      $userId = $userRecord->getId();
      $this->client->request('GET', "/api/users/$userId");

      $this->assertResponseIsSuccessful();
      $this->assertJsonContains([
         '@context' => '/api/contexts/User',
         '@type' => 'User',
         'username' => 'test',
         'email' => 'test@example.com'
      ]);
   }

   public function testPatch(): void
   {
      $this->client->request('POST', '/api/users', [
         'json' => [
            'username' => 'test',
            'email' => 'test@example.com',
            'password' => 'test1234'
         ]
      ]);

      $userRecord = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'test']);
      $userId = $userRecord->getId();

      $this->client->request('PATCH', "/api/users/$userId", [
         'json' => [
            'username' => 'admin',
         ],
         'headers' => [
            'content-type' => 'application/merge-patch+json'
         ]
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertJsonContains([
         '@context' => '/api/contexts/User',
         '@type' => 'User',
         'username' => 'admin',
         'email' => 'test@example.com'
      ]);
   }
}
