<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Drink;
use App\Entity\Rating;
use App\Tests\DatabaseTestCase;

class RatingTest extends DatabaseTestCase
{
   public function test_rating_can_be_created(): void
   {
      $rating = new Rating();
      $rating->setRating(2);

      $this->entityManager->persist($rating);
      $this->entityManager->flush();

      $ratingRecord = $this->entityManager->getRepository(Rating::class)
         ->findOneBy(['id' => $rating->getId()]);

      $this->assertSame(2, $ratingRecord->getRating());
   }

   public function test_drink_can_be_added(): void
   {
      $drink = new Drink();
      $drink->setName('mohito');
      $drink->setDescription('test description');
      $drink->setPreparation('test preparation');
      $drink->setImage('test address');
      $drink->setAuthor($this->createUser());
      $this->entityManager->persist($drink);

      $rating = new Rating();
      $rating->setRating(2);
      $rating->setDrink($drink);
      $this->entityManager->persist($rating);

      $this->entityManager->flush();

      $ratingRecord = $this->entityManager->getRepository(Rating::class)
         ->findOneBy(['id' => $rating->getId()]);

      $this->assertSame('mohito', $ratingRecord->getDrink()->getName());
   }

   public function test_user_can_be_added(): void
   {
      $user = $this->createUser();
      $this->entityManager->persist($user);

      $rating = new Rating();
      $rating->setRating(2);
      $rating->setUser($user);
      $this->entityManager->persist($rating);

      $this->entityManager->flush();

      $ratingRecord = $this->entityManager->getRepository(Rating::class)
         ->findOneBy(['id' => $rating->getId()]);

      $this->assertSame('test', $ratingRecord->getUser()->getUserName());
   }
}
