<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Rating;
use App\Tests\DatabaseTestCase;

class RatingTest extends DatabaseTestCase
{
   public function test_rating_can_be_created(): void
   {
      $rating = new Rating();
      $rating->setRating(2);
      $rating->setDrink($this->createDrink('mohito'));
      $rating->setUser($this->createUser('author', 'author@test.com'));

      $this->entityManager->persist($rating);
      $this->entityManager->flush();

      $ratingRecord = $this->entityManager->getRepository(Rating::class)
         ->findOneBy(['id' => $rating->getId()]);

      $this->assertSame(2, $ratingRecord->getRating());
   }
}
