<?php

namespace App\Doctrine;

use App\Entity\Drink;
use Symfony\Component\Security\Core\Security;

class DrinkSetAuthorListener 
{
   public function __construct(private Security $security)
   {
   }

   public function prePersist(Drink $drink) 
   {
      if ($drink->getAuthor()) {
         return;
      }

      if ($this->security->getUser()) {
         $drink->setAuthor($this->security->getUser());
      }
   }
}