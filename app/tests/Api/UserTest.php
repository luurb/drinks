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

   public function test_check_if_blank_validation_work(): void
   {
      $this->client->request('POST', '/api/users', [
         'json' => [
            'username' => '',
            'email' => 'test@example.com',
            'password' => 'test1234'
         ]
      ]);

      $this->assertResponseStatusCodeSame(422);

      $this->client->request('POST', '/api/users', [
         'json' => [
            'username' => 'test',
            'email' => '',
            'password' => 'test1234'
         ]
      ]);

      $this->assertResponseStatusCodeSame(422);

      $this->client->request('POST', '/api/users', [
         'json' => [
            'username' => 'test',
            'email' => 'test@example.com',
            'password' => ''
         ]
      ]);
      $this->assertResponseStatusCodeSame(422);
   }

   public function test_check_if_email_validation_work(): void
   {
      $this->client->request('POST', '/api/users', [
         'json' => [
            'username' => 'test',
            'email' => 'example.com',
            'password' => 'test123345'
         ]
      ]);
      $this->assertResponseStatusCodeSame(422);
      $this->assertJsonContains([
         'violations' => [
            [
               'propertyPath' => 'email',
               'message' => 'This value is not a valid email address.',

            ]
         ]
      ]);
   }

   public function test_check_if_length_validation_work(): void
   {
      $faker = Factory::create();

      $this->client->request('POST', '/api/users', [
         'json' => [
            'username' => 'tt',
            'email' => 'test@example.com',
            'password' => '345'
         ]
      ]);
      $this->assertResponseStatusCodeSame(422);
      $this->assertJsonContains([
         'violations' => [
            [
               'propertyPath' => 'username',
               'message' => 'This value is too short. It should have 4 characters or more.',
            ],
            [
               'propertyPath' => 'password',
               'message' => 'This value is too short. It should have 4 characters or more.',
            ]
         ]
      ]);

      $this->client->request('POST', '/api/users', [
         'json' => [
            'username' => $faker->realTextBetween(26),
            'email' => 'test@example.com',
            'password' => $faker->realTextBetween(31),
         ]
      ]);
      $this->assertResponseStatusCodeSame(422);
      $this->assertJsonContains([
         'violations' => [
            [
               'propertyPath' => 'username',
               'message' => 'This value is too long. It should have 25 characters or less.',
            ],
            [
               'propertyPath' => 'password',
               'message' => 'This value is too long. It should have 30 characters or less.',
            ]
         ]
      ]);
   }
}
