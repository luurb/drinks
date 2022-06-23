<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Drink;

class DrinkTest extends ApiTestCase
{
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testGetCollection(): void
    {
        $this->client->request('GET', '/api/drinks');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            "@context" => "/api/contexts/Drink",
            "@id" => "/api/drinks",
            "@type" => "hydra:Collection",
            "hydra:member" => [],
            "hydra:totalItems" => 0
        ]);
    }

    public function testPOST(): void
    {
        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'mohito',
                'description' => 'test description',
                'preparation' => 'test preparation',
                'image' => '../images'
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Drink',
            '@type' => 'Drink',
            'name' => 'test',

            'name' => 'mohito',
            'description' => 'test description',
            'preparation' => 'test preparation',
            'image' => '../images',
            'products' => [],
            'categories' => []
        ]);
    }

    public function testRetrieveDrink(): void
    {
        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'mohito',
                'description' => 'test description',
                'preparation' => 'test preparation',
                'image' => '../images'
            ]
        ]);

        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $drinkRecord = $entityManager->getRepository(Drink::class)->findOneBy(['name' => 'mohito']);
        $drinkId = $drinkRecord->getId();
        $this->client->request('GET', "/api/drinks/$drinkId");

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'name' => 'mohito',
            'description' => 'test description',
            'preparation' => 'test preparation',
            'image' => '../images'
        ]);
    }

    public function testPUT(): void
    {
        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'mohito',
                'description' => 'test description',
                'preparation' => 'test preparation',
                'image' => '../images'
            ]
        ]);

        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $drinkRecord = $entityManager->getRepository(Drink::class)->findOneBy(['name' => 'mohito']);
        $drinkId = $drinkRecord->getId();

        $this->client->request('PUT', "/api/drinks/$drinkId", [
            'json' => [
                'name' => 'update',
                'description' => 'update desc',
                'preparation' => 'update preparation',
                'image' => 'update ../images'
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'name' => 'update',
            'description' => 'update desc',
            'preparation' => 'update preparation',
            'image' => 'update ../images'
        ]);
    }

    public function testPATCH(): void
    {
        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'mohito',
                'description' => 'test description',
                'preparation' => 'test preparation',
                'image' => '../images'
            ]
        ]);

        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $drinkRecord = $entityManager->getRepository(Drink::class)->findOneBy(['name' => 'mohito']);
        $drinkId = $drinkRecord->getId();

        $this->client->request('PATCH', "/api/drinks/$drinkId", [
            'json' => [
                'name' => 'update',
                'description' => 'update desc',
                'preparation' => 'update preparation',
                'image' => 'update ../images'
            ],
            'headers' => [
                'content-type' => 'application/merge-patch+json'
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'name' => 'update',
            'description' => 'update desc',
            'preparation' => 'update preparation',
            'image' => 'update ../images'
        ]);
    }

    public function testDELETE(): void
    {
        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'mohito',
                'description' => 'test',
                'preparation' => 'test',
                'image' => '../images'
            ]
        ]);

        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $drinkRecord = $entityManager->getRepository(Drink::class)->findOneBy(['name' => 'mohito']);
        $drinkId = $drinkRecord->getId();

        $this->client->request('DELETE', "/api/drinks/$drinkId");

        $this->assertResponseStatusCodeSame(204);
    }
}
