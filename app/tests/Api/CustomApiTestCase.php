<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class CustomApiTestCase extends ApiTestCase
{
    protected function createUser(string $userName, string $password, array $roles = []): User
    {
        $user = new User();
        $user->setEmail($userName . '@test.com');
        $user->setUsername($userName);
        $hasher = new UserPasswordHasher(static::getContainer()->get('security.password_hasher_factory'));
        $hashedPassword = $hasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles($roles);
        $em = static::getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();
        return $user;
    }

    protected function logIn(Client $client, string $userName, string $password)
    {
        $client->request('POST', '/api/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => $userName,
                'password' => $password
            ],
        ]);
        $this->assertResponseStatusCodeSame(204);
    }

    protected function createUserAndLogIn(Client $client, string $userName, string $password): User
    {
        $user = $this->createUser($userName, $password);
        $this->logIn($client, $userName, $password);

        return $user;
    }
}
