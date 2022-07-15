<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\DenormalizedIdentifiersAwareItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Drink;
use App\Entity\Rating;
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

      return $drink;
   }


   private function getAvgRating(Drink $drink)
   {
      $queryBuilder = $this->doctrine->getManagerForClass(Rating::class)
         ->getRepository(Rating::class)->createQueryBuilder('rating');
      
      $avgRating = $queryBuilder
         ->andWhere('rating.drink = :drinkId')
         ->setParameter('drinkId', $drink)
         ->select('AVG(rating.rating)')
         ->getQuery()
         ->getSingleScalarResult();
      
      if (!$avgRating) {
         $avgRating = 0.0;
      }

      return round($avgRating, 2);
   }
}
