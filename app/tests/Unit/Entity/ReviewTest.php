<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Review;
use App\Tests\DatabaseTestCase;

class ReviewTest extends DatabaseTestCase
{
   public function test_review_can_be_created(): void
   {
      $review = new Review();
      $review->setReview('review');
      $review->setTitle('test');
      $review->setAuthor($this->createUser('author', 'author@test.com'));
      $review->setDrink($this->createDrink('mohito'));

      $this->entityManager->persist($review);
      $this->entityManager->flush();

      $savedReview = $this->entityManager->getRepository(Review::class)
         ->findOneBy(['title' => 'test']);

      $this->assertSame('review', $savedReview->getReview());
   }
}