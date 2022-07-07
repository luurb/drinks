<?php

namespace App\Tests;

use App\Entity\Drink;
use App\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\User;

abstract class DatabaseTestCase extends KernelTestCase
{
    /** @var EntityManager*/
    protected $entityManager;

    protected function setUp(): void
    {
        $kernel =  self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    protected function createUser(): User
    {
        $user = new User();
        $user->setEmail('test@test.com');
        $user->setUsername('test');
        $user->setPassword('12345');
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    protected function createDrink(string $name): Drink
    {
        $drink = new Drink();
        $drink->setName($name);
        $drink->setDescription('test description');
        $drink->setPreparation('test preparation');
        $drink->setImage('test address');
        $drink->setAuthor($this->createUser());
        $this->entityManager->persist($drink);
        $this->entityManager->flush();
        return $drink;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}