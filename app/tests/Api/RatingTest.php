<?php

namespace App\Tests\Api;

use App\Entity\Drink;
use App\Entity\Rating;
use App\Entity\User;

class RatingTest extends CustomApiTestCase
{
   private $entityManager;
   private $client;

   protected function setUp(): void
   {
      parent::setUp();
      $this->client = static::createClient();
      $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
   }

   public function createDrink(string $name, User $user): Drink
   {
      $drink = new Drink();
      $drink->setName($name);
      $drink->setDescription('test description');
      $drink->setPreparation('test preparation');
      $drink->setImage('../images');
      $drink->setAuthor($user);

      $this->entityManager->persist($drink);
      $this->entityManager->flush();

      return $drink;
   }

   public function createRating(int $userRating, ?User $user = null): Rating
   {
      if (!$user) {
         $user = $this->createUserAndLogIn($this->client, 'test', '1234');
      }

      $rating = new Rating();
      $rating->setRating($userRating);
      $drink = $this->createDrink('mohito', $user);
      $rating->setDrink($drink);
      $rating->setUser($user);
      $this->entityManager->persist($rating);
      $this->entityManager->flush();

      return $rating;
   }

   public function testRetrieveCollection(): void
   {
      $this->client->request('GET', '/api/ratings');
      $this->assertResponseStatusCodeSame(401);

      $this->createUser('admin', '1234', ['ROLE_ADMIN']);
      $this->logIn($this->client, 'admin', '1234');

      $this->client->request('GET', 'api/ratings');
      $this->assertResponseIsSuccessful();
   }

   public function testRetrieveRating(): void
   {
      $rating = $this->createRating(3);

      $this->client->request('GET', '/api/ratings/' . $rating->getId());
      $this->assertResponseIsSuccessful();
      $this->assertJsonContains([
         'rating' => 3
      ]);
   }

   public function testPost(): void
   {
      $user = $this->createUserAndLogIn($this->client, 'test', '12345');
      $user2 = $this->createUser('test2', 'test');

      $drink = $this->createDrink('mohito', $user);

      $this->client->request('POST', '/api/ratings', [
         'json' => [
            'rating' => 2,
            'drink' => '/api/drinks/' . $drink->getId(),
            'user' => '/api/users/' . $user2->getId()
         ]
      ]);
      $this->assertResponseStatusCodeSame(422, 'Not passing currently logged in user');

      $this->client->request('POST', '/api/ratings', [
         'json' => [
            'rating' => 2,
            'drink' => '/api/drinks/' . $drink->getId(),
            'user' => '/api/users/' . $user->getId()
         ]
      ]);
      $this->assertResponseStatusCodeSame(201);

      $this->client->request('POST', '/api/ratings', [
         'json' => [
            'rating' => 3,
            'drink' => '/api/drinks/' . $drink->getId(),
            'user' => '/api/users/' . $user->getId()
         ]
      ]);
      $this->assertResponseStatusCodeSame(422, 'This drink is already rated');
   }

   public function testPut(): void
   {
      $user = $this->createUserAndLogIn($this->client, 'test', 'test');
      $rating = $this->createRating(3, $user);

      $this->client->request('PUT', '/api/ratings/' . $rating->getId(), [
         'json' => [
            'rating' => 4
         ]
      ]);
      $this->assertResponseIsSuccessful();
      $this->assertJsonContains([
         'rating' => 4
      ]);

      $this->createUserAndLogIn($this->client, 'test2', 'test');
      $this->client->request('PUT', '/api/ratings/' . $rating->getId(), [
         'json' => [
            'rating' => 5
         ]
      ]);
      $this->assertResponseStatusCodeSame(403);

      $this->createUser('admin', '1234', ['ROLE_ADMIN']);
      $this->logIn($this->client, 'admin', '1234');
      $this->client->request('PUT', '/api/ratings/' . $rating->getId(), [
         'json' => [
            'rating' => 5
         ]
      ]);
      $this->assertResponseIsSuccessful();
      $this->assertJsonContains([
         'rating' => 5
      ]);
   }

   public function testPatch(): void
   {
      $user = $this->createUserAndLogIn($this->client, 'test', 'test');
      $rating = $this->createRating(3, $user);

      $this->client->request('PATCH', '/api/ratings/' . $rating->getId(), [
         'json' => [
            'rating' => 4
         ],
         'headers' => [
            'content-type' => 'application/merge-patch+json'
         ]
      ]);
      $this->assertResponseIsSuccessful();
      $this->assertJsonContains([
         'rating' => 4
      ]);

      $this->createUserAndLogIn($this->client, 'test2', 'test');
      $this->client->request('PATCH', '/api/ratings/' . $rating->getId(), [
         'json' => [
            'rating' => 5 
         ],
         'headers' => [
            'content-type' => 'application/merge-patch+json'
         ]
      ]);
      $this->assertResponseStatusCodeSame(403);

      $this->createUser('admin', '1234', ['ROLE_ADMIN']);
      $this->logIn($this->client, 'admin', '1234');
      $this->client->request('PATCH', '/api/ratings/' . $rating->getId(), [
         'json' => [
            'rating' => 5 
         ],
         'headers' => [
            'content-type' => 'application/merge-patch+json'
         ]
      ]);
      $this->assertResponseIsSuccessful();
      $this->assertJsonContains([
         'rating' => 5
      ]);
   }

   public function testDelete(): void
   {
      $rating = $this->createRating(3);
      $this->client->request('DELETE', '/api/ratings/' . $rating->getId());
      $this->assertResponseStatusCodeSame(403);

      $this->createUser('admin', 'admin', ['ROLE_ADMIN']);
      $this->logIn($this->client, 'admin', 'admin');
      $this->client->request('DELETE', '/api/ratings/' . $rating->getId());
      $this->assertResponseStatusCodeSame(204);
   }
}
