<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataPersister implements DataPersisterInterface
{
   public function __construct(
      private EntityManagerInterface $entityManager,
      private UserPasswordHasherInterface $hasher
   ) {
   }

   public function supports($data): bool
   {
      return $data instanceof User;
   }

   /**
    * @param User $data
    * @return void
    */
   public function persist($data)
   {
      if ($data->getPlainPassword()) {
         $data->setPassword(
            $this->hasher->hashPassword($data, $data->getPlainPassword())
         );
         $data->eraseCredentials();
      }

      $this->entityManager->persist($data);
      $this->entityManager->flush();
   }

   public function remove($data)
   {
      $this->entityManager->remove(($data));
      $this->entityManager->flush();
   }
}
