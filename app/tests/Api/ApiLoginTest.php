<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

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
}
