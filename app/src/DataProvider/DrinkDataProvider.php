<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\DenormalizedIdentifiersAwareItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Drink;
use App\Entity\Rating;
use App\Entity\Review;
use Doctrine\Persistence\ManagerRegistry;

final class DrinkDataProvider implements
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
      return Drink::class === $resourceClass;
   }

   public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
   {
      $drinks = $this->collectionDataProvider->getCollection(
         $resourceClass,
         $operationName,
         $context
      );

      foreach ($drinks as $drink) {
         $drink->setAvgRating($this->getAvgRating($drink));
         $drink->setReviewsNumber($this->getReviewsNumber($drink));
         $drink->setRatingsNumber($this->getRatingsNumber($drink));
      }

      return $drinks;
   }

   public function getItem(string $resourceClass, $id, ?string $operationName = null, array $context = [])
   {
      $drink = $this->itemDataProvider->getItem(
         $resourceClass,
         $id,
         $operationName,
         $context
      );

      if (!$drink) {
         return null;
      }

      $drink->setAvgRating($this->getAvgRating($drink));
      $ratingsStats = $this->getRatingStats($drink);
      $drink->setRatingsStat($this->getRatingStats($drink));
      $drink->setRatingsNumber(array_sum($ratingsStats));
      $drink->setReviewsNumber($this->getReviewsNumber($drink));

      return $drink;
   }


   private function getAvgRating(Drink $drink): float
   {
      $queryBuilder = $this->doctrine->getManagerForClass(Rating::class)
         ->getRepository(Rating::class)->createQueryBuilder('rating');

      $avgRating = $queryBuilder
         ->andWhere('rating.drink = :drink')
         ->setParameter('drink', $drink)
         ->select('AVG(rating.rating)')
         ->getQuery()
         ->getSingleScalarResult();

      if (!$avgRating) {
         $avgRating = 0.0;
      }

      return round($avgRating, 2);
   }

   private function getRatingStats(Drink $drink): array
   {
      $ratingStats = [];

      $queryBuilder = $this->doctrine->getManagerForClass(Rating::class)
         ->getRepository(Rating::class)->createQueryBuilder('rating')
         ->andWhere('rating.drink = :drink')
         ->andWhere('rating.rating = :rating')
         ->select('COUNT(rating.id)');

      for ($i = 1; $i < 6; $i++) {
         $ratingStats[$i] = $queryBuilder
            ->setParameter('drink', $drink)
            ->setParameter('rating', $i)
            ->getQuery()
            ->getSingleScalarResult();
      }

      return $ratingStats;
   }

   private function getReviewsNumber(Drink $drink): int
   {
      $reviewsNumber = $this->doctrine->getManagerForClass(Review::class)
         ->getRepository(Review::class)->createQueryBuilder('review')
         ->andWhere('review.drink = :drink')
         ->setParameter('drink', $drink)
         ->select('COUNT(review.id)')
         ->getQuery()
         ->getSingleScalarResult();

      return $reviewsNumber;
   }

   private function getRatingsNumber(Drink $drink): int
   {
      $ratingsNumber = $this->doctrine->getManagerForClass(Rating::class)
         ->getRepository(Rating::class)->createQueryBuilder('rating')
         ->andWhere('rating.drink = :drink')
         ->setParameter('drink', $drink)
         ->select('COUNT(rating.id)')
         ->getQuery()
         ->getSingleScalarResult();

      return $ratingsNumber;
   }
}
