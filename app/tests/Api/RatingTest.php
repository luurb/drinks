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

   public function createRating(int $userRating): Rating
   {
      $rating = new Rating();
      $rating->setRating($userRating);
      $this->entityManager->persist($rating);
      $this->entityManager->flush();

      return $rating;
   }

   public function testPost(): void
   {
      $user = $this->createUserAndLogIn($this->client, 'test', '12345');
      $drink = $this->createDrink('mohito', $user);

      $rating = new Rating();
      $rating->setRating(2);
      $rating->setDrink($drink);
      $rating->setUser($user);

      $this->client->request('POST', '/api/ratings', [
         'json' => [
            'rating' => 2,
            'drink' => '/api/drinks/' . $drink->getId(),
            'user' => '/api/users/' . $user->getId()
         ]
      ]);
      $this->assertResponseStatusCodeSame(201);
   }
}
