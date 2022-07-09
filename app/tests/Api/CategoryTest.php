<?php

namespace App\Tests\Api;

use App\Entity\Category;

class CategoryTest extends CustomApiTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
    }

    public function createCategory(string $name): Category
    {
        $category = new Category();
        $category->setName($name);
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }

    public function testGetCategoryByName(): void
    {
        $this->createCategory('słodki');

        $this->client->request('GET', '/api/categories/słodki');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Category',
            '@type' => 'Category',
            'name' => 'słodki'
        ]);
    }

    public function testGetCollection(): void
    {
        $this->client->request('GET', '/api/categories');
        $this->assertResponseIsSuccessful();
    }

    public function testPost(): void
    {
        //Anonymous user
        $this->client->request('POST', '/api/categories', [
            'json' => [
                'name' => 'test'
            ]
        ]);
        $this->assertresponsestatuscodesame(401);

        //Logged withour admin role
        $this->createUserAndLogIn($this->client, 'test', '12345');
        $this->client->request('POST', '/api/categories', [
            'json' => [
                'name' => 'test'
            ]
        ]);
        $this->assertresponsestatuscodesame(403);

        //Admin
        $this->createUser('admin', '12345', ['ROLE_ADMIN']);
        $this->logIn($this->client, 'admin', '12345');
        $this->client->request('POST', '/api/categories', [
            'json' => [
                'name' => 'test'
            ]
        ]);
        $this->assertResponseIsSuccessful();
    }

    public function testPut(): void
    {
        $this->createCategory('słodki');

        //Anonymous user
        $this->client->request('PUT', '/api/categories/słodki', [
            'json' => [
                'name' => 'test'
            ]
        ]);
        $this->assertresponsestatuscodesame(401);

        //Logged withour admin role
        $this->createUserAndLogIn($this->client, 'test', '12345');
        $this->client->request('PUT', '/api/categories/słodki', [
            'json' => [
                'name' => 'test'
            ]
        ]);
        $this->assertresponsestatuscodesame(403);

        //Admin
        $this->createUser('admin', '12345', ['ROLE_ADMIN']);
        $this->logIn($this->client, 'admin', '12345');
        $this->client->request('PUT', '/api/categories/słodki', [
            'json' => [
                'name' => 'test'
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'name' => 'test'
        ]);
    }

    public function testPatch(): void
    {
        $this->createCategory('słodki');

        //Anonymous user
        $this->client->request('PATCH', '/api/categories/słodki', [
            'json' => [
                'name' => 'test'
            ],
            'headers' => [
                'content-type' => 'application/merge/patch+json'
            ]
        ]);
        $this->assertresponsestatuscodesame(401);

        //Logged withour admin role
        $this->createUserAndLogIn($this->client, 'test', '12345');
        $this->client->request('PATCH', '/api/categories/słodki', [
            'json' => [
                'name' => 'test'
            ],
            'headers' => [
                'content-type' => 'application/merge-patch+json'
            ]
        ]);
        $this->assertresponsestatuscodesame(403);

        //Admin
        $this->createUser('admin', '12345', ['ROLE_ADMIN']);
        $this->logIn($this->client, 'admin', '12345');
        $this->client->request('PATCH', '/api/categories/słodki', [
            'json' => [
                'name' => 'test'
            ],
            'headers' => [
                'content-type' => 'application/merge-patch+json'
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'name' => 'test'
        ]);
    }

    public function testDelete(): void
    {
        $this->createCategory('słodki');

        //Anonymous
        $this->client->request('DELETE', '/api/categories/słodki');
        $this->assertResponseStatusCodeSame(401);

        //Logged withour admin role
        $this->createUserAndLogIn($this->client, 'test', '12345');
        $this->client->request('DELETE', '/api/categories/słodki');
        $this->assertResponseStatusCodeSame(403);

        //Logged user with admin role
        $this->createUser('admin', '12345', ['ROLE_ADMIN']);
        $this->logIn($this->client, 'admin', '12345');
        $this->client->request('DELETE', '/api/categories/słodki');
        $this->assertResponseStatusCodeSame(204);
    }
}
