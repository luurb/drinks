<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Drink;
use App\Tests\DatabaseTestCase;

class DrinkTest extends DatabaseTestCase
{
    public function test_drink_record_can_be_created_in_database(): void
    {
        $drink = new Drink();
        $drink->setName('mohito');
        $drink->setDescription('test description');
        $drink->setPreparation('test preparation');
        $drink->setImage('test address');

        $this->entityManager->persist($drink);
        $this->entityManager->flush();

        $drinkRepo = $this->entityManager->getRepository(Drink::class);
        $drinkRecord = $drinkRepo->findOneBy(['name' => 'mohito']);

        $this->assertSame('mohito', $drinkRecord->getName());
        $this->assertSame('test description', $drinkRecord->getDescription());
        $this->assertSame('test preparation', $drinkRecord->getPreparation());
        $this->assertSame('test address', $drinkRecord->getImage());
    }
}
