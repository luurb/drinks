<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\DenormalizedIdentifiersAwareItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Rating;
use App\Entity\Review;
use Doctrine\Persistence\ManagerRegistry;

final class ReviewDataProvider implements
   ContextAwareCollectionDataProviderInterface,
   DenormalizedIdentifiersAwareItemDataProviderInterface,
   RestrictedDataProviderInterface
{
   public function __construct(
      private CollectionDataProviderInterface $collectionDataProvider,
      private ItemDataProviderInterface $itemDataProvider,
      private ManagerRegistry $doctrine
   ) {
   }

   public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
   {
      return Review::class === $resourceClass;
   }

   public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
   {
      $reviews = $this->collectionDataProvider->getCollection(
         $resourceClass,
         $operationName,
         $context
      );

      return $reviews;
   }

   public function getItem(string $resourceClass, $id, ?string $operationName = null, array $context = [])
   {
      $review = $this->itemDataProvider->getItem(
         $resourceClass,
         $id,
         $operationName,
         $context
      );

      if (!$review) {
         return null;
      }

      $review->setDrinkRating($this->getDrinkRating($review));

      return $review;
   }


   private function getDrinkRating(Review $review): int
   {
      $drink = $review->getDrink();
      $user = $review->getAuthor();

      $queryBuilder = $this->doctrine->getManagerForClass(Rating::class)
         ->getRepository(Rating::class)->createQueryBuilder('rating');

      $rating = $queryBuilder
         ->andWhere('rating.drink = :drink')
         ->setParameter('drink', $drink)
         ->andWhere('rating.user = :user')
         ->setParameter('user', $user)
         ->select('(rating.rating)')
         ->getQuery()
         ->getResult();
      
      if (!$rating) {
         $rating = 0;
      } else {
         $rating = $rating[0][1];
      }

      return $rating;
   }
}
