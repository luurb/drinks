<?php

namespace App\Tests\Api;

use App\Entity\Drink;
use App\Entity\Rating;
use App\Entity\Review;
use App\Entity\User;

class ReviewTest extends CustomApiTestCase
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

   public function createReview(
      string $reviewText,
      string $title,
      ?User $user = null
   ): Review {
      if (!$user) {
         $user = $this->createUser('test', '1234');
      }
      $drink = $this->createDrink('mohito', $user);

      $review = new Review();
      $review->setReview($reviewText);
      $review->setTitle($title);
      $review->setAuthor($user);
      $review->setDrink($drink);

      $this->entityManager->persist($review);
      $this->entityManager->flush();

      return $review;
   }

   public function testRetrieveCollection(): void
   {
      $this->client->request('GET', '/api/reviews');
      $this->assertResponseStatusCodeSame(401);

      $this->createUser('admin', '1234', ['ROLE_ADMIN']);
      $this->logIn($this->client, 'admin', '1234');

      $this->client->request('GET', '/api/reviews');
      $this->assertResponseIsSuccessful();
   }

   public function testRetrieveReview(): void
   {
      $review = $this->createReview('review', 'test');

      $this->client->request('GET', '/api/reviews/' . $review->getId());
      $this->assertResponseIsSuccessful();
      $this->assertJsonContains([
         'title' => 'test'
      ]);
   }

   public function testPost(): void
   {
      $user = $this->createUser('user', '1234');
      $drink = $this->createDrink('mohito', $user);

      $this->client->request('POST', '/api/reviews', [
         'json' => [
            'review' => 'review',
            'title' => 'title',
            'author' => '/api/users/' . $user->getId(),
            'drink' => '/api/drinks/' . $drink->getId()
         ]
      ]);
      $this->assertResponseStatusCodeSame(401);

      $user2 = $this->createUserAndLogIn($this->client, 'user1', '12345');
      $this->client->request('POST', '/api/reviews', [
         'json' => [
            'review' => 'review',
            'title' => 'title',
            'author' => '/api/users/' . $user->getId(),
            'drink' => '/api/drinks/' . $drink->getId()
         ]
      ]);
      $this->assertResponseStatusCodeSame(422, 'Not passing currently logged in user');

      $this->client->request('POST', '/api/reviews', [
         'json' => [
            'review' => 'review',
            'title' => 'title',
            'author' => '/api/users/' . $user2->getId(),
            'drink' => '/api/drinks/' . $drink->getId()
         ]
      ]);
      $this->assertResponseIsSuccessful();

      $this->client->request('POST', '/api/reviews', [
         'json' => [
            'review' => 'review',
            'title' => 'title',
            'author' => '/api/users/' . $user2->getId(),
            'drink' => '/api/drinks/' . $drink->getId()
         ]
      ]);
      $this->assertResponseStatusCodeSame(422, 'This drink is already reviewed');
   }

   public function testPut(): void
   {
      $user = $this->createUserAndLogIn($this->client, 'user', '1234');
      $review = $this->createReview('review', 'title', $user);

      $this->client->request('PUT', '/api/reviews/' . $review->getId(), [
         'json' => [
            'title' => 'newTitle'
         ]
      ]);
      $this->assertResponseIsSuccessful();
      $this->assertJsonContains([
         'title' => 'newTitle'
      ]);

      $this->createUserAndLogIn($this->client, 'test2', 'test');
      $this->client->request('PUT', '/api/reviews/' . $review->getId(), [
         'json' => [
            'title' => 'test2'
         ]
      ]);
      $this->assertResponseStatusCodeSame(403);

      $this->createUser('admin', '1234', ['ROLE_ADMIN']);
      $this->logIn($this->client, 'admin', '1234');
      $this->client->request('PUT', '/api/reviews/' . $review->getId(), [
         'json' => [
            'title' => 'test2'
         ]
      ]);
      $this->assertResponseIsSuccessful();
      $this->assertJsonContains([
         'title' => 'test2'
      ]);
   }

   public function testPatch(): void
   {
      $user = $this->createUserAndLogIn($this->client, 'user', '1234');
      $review = $this->createReview('review', 'title', $user);

      $this->client->request('PATCH', '/api/reviews/' . $review->getId(), [
         'json' => [
            'title' => 'newTitle'
         ],
         'headers' => [
            'content-type' => 'application/merge-patch+json'
         ]
      ]);
      $this->assertResponseIsSuccessful();
      $this->assertJsonContains([
         'title' => 'newTitle'
      ]);

      $this->createUserAndLogIn($this->client, 'test2', 'test');
      $this->client->request('PATCH', '/api/reviews/' . $review->getId(), [
         'json' => [
            'title' => 'newTitle'
         ],
         'headers' => [
            'content-type' => 'application/merge-patch+json'
         ]
      ]);
      $this->assertResponseStatusCodeSame(403);

      $this->createUser('admin', '1234', ['ROLE_ADMIN']);
      $this->logIn($this->client, 'admin', '1234');
      $this->client->request('PATCH', '/api/reviews/' . $review->getId(), [
         'json' => [
            'title' => 'test2'
         ],
         'headers' => [
            'content-type' => 'application/merge-patch+json'
         ]
      ]);
      $this->assertResponseIsSuccessful();
      $this->assertJsonContains([
         'title' => 'test2'
      ]);
   }

   public function testDelete(): void
   {
      $user = $this->createUserAndLogIn($this->client, 'user', '12345');
      $review= $this->createReview('review', 'title', $user);
      $this->client->request('DELETE', '/api/reviews/' . $review->getId());
      $this->assertResponseStatusCodeSame(403);

      $this->createUser('admin', 'admin', ['ROLE_ADMIN']);
      $this->logIn($this->client, 'admin', 'admin');
      $this->client->request('DELETE', '/api/reviews/' . $review->getId());
      $this->assertResponseStatusCodeSame(204);
   }

   public function test_get_correct_drink_rating(): void
   {
      $user = $this->createUser('test', '1234');
      $drink = $this->createDrink('mohito', $user);

      $review = new Review();
      $review->setReview('review');
      $review->setTitle('title');
      $review->setAuthor($user);
      $review->setDrink($drink);

      $this->entityManager->persist($review);
      $this->entityManager->flush();

      $this->client->request('GET', '/api/reviews/' . $review->getId());
      $this->assertJsonContains([
         'drinkRating' => 0
      ]);

      $rating = new Rating();
      $rating->setRating(4);
      $rating->setDrink($drink);
      $rating->setUser($user);

      $this->entityManager->persist($rating);
      $this->entityManager->flush();

      $this->client->request('GET', '/api/reviews/' . $review->getId());
      $this->assertJsonContains([
         'drinkRating' => 4
      ]);

   }
}
