<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;

class ApiLoginTest extends ApiTestCase
{
   private $client;
   private $entityManager;

   protected function setUp(): void
   {
      parent::setUp();
      $this->client = static::createClient();
      $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
   }

   public function test_not_correct_content_type(): void
   {
      $this->client->request('POST', '/api/login', [
         'headers' => [
            'content-type' => 'application/x-www-form-urlencoded'
         ]
      ]);

      $this->assertResponseStatusCodeSame(400);
      $this->assertJsonContains([
         'error' => 'Nieprawidłowy request: sprawdź czy Content-Type to "application/json"',
      ]);
   }

   public function test_retrieve_authenticated_user_iri(): void
   {
      $user = new User();
      $user->setEmail('test@test.com');
      $user->setUsername('test');
      $user->setPassword('$2y$13$5KV5tdM7rkOdxOC4MTsazOivcYChXJbnYmq3p8ZSHbNWAmf99Vqz6');

      $this->entityManager->persist($user);
      $this->entityManager->flush();

      $userId = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'test'])->getId();

      $this->client->request('POST', '/api/login', [
         'json' => [
            'username' => 'test',
            'password' => 'test'
         ]
      ]);

      $this->assertResponseStatusCodeSame(204);
      $this->assertResponseHasHeader('Location', "/api/user/$userId");
   }
}
