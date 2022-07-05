<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use App\Tests\DatabaseTestCase;

class UserTest extends DatabaseTestCase
{
    public function test_user_record_can_be_created_in_database(): void
    {
        $user= new User();
        $user->setUsername('test');
        $user->setEmail('test@example.com');
        $user->setPassword('test12345');
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $userRepo = $this->entityManager->getRepository(User::class);
        $user = $userRepo->findOneBy(['username' => 'test']);

        $this->assertSame('test', $user->getUserName());
        $this->assertSame('test@example.com', $user->getEmail());
    }
}
