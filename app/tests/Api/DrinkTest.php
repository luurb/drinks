<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\ProductFixtures;
use App\Entity\Drink;

class DrinkTest extends ApiTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
    }

    public function testGetCollection(): void
    {
        $this->client->request('GET', '/api/drinks');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Drink',
            '@id' => '/api/drinks',
            '@type' => 'hydra:Collection',
            'hydra:member' => [],
            'hydra:totalItems' => 0
        ]);
    }

    public function testPost(): void
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

        $drinkRecord = $this->entityManager->getRepository(Drink::class)->findOneBy(['name' => 'mohito']);
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

    public function testPut(): void
    {
        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'mohito',
                'description' => 'test description',
                'preparation' => 'test preparation',
                'image' => '../images'
            ]
        ]);

        $drinkRecord = $this->entityManager->getRepository(Drink::class)->findOneBy(['name' => 'mohito']);
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

    public function testPatch(): void
    {
        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'mohito',
                'description' => 'test description',
                'preparation' => 'test preparation',
                'image' => '../images'
            ]
        ]);

        $drinkRecord = $this->entityManager->getRepository(Drink::class)->findOneBy(['name' => 'mohito']);
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

    public function testDelete(): void
    {
        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'mohito',
                'description' => 'test',
                'preparation' => 'test',
                'image' => '../images'
            ]
        ]);

        $drinkRecord = $this->entityManager->getRepository(Drink::class)->findOneBy(['name' => 'mohito']);
        $drinkId = $drinkRecord->getId();

        $this->client->request('DELETE', "/api/drinks/$drinkId");

        $this->assertResponseStatusCodeSame(204);
    }

    public function test_return_drinks_which_contain_specific_products_and_categories(): void
    {
        $productFixture = new ProductFixtures();
        $categoryFixture = new CategoryFixtures();
        $productFixture->load($this->entityManager);
        $categoryFixture->load($this->entityManager);

        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'drink1',
                'description' => 'description',
                'preparation' => 'preparation',
                'image' => '../images',
                'categories' => [
                    '/api/categories/orzeźwiający'
                ],
                'products' => [
                    '/api/products/wódka',
                    '/api/products/sok%20wiśniowy',
                    '/api/products/mięta'
                ]
            ]
        ]);

        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'drink2',
                'description' => 'description',
                'preparation' => 'preparation',
                'image' => '../images',
                'categories' => [
                    '/api/categories/kwaśny',
                    '/api/categories/słodki'
                ],
                'products' => [
                    '/api/products/wódka',
                    '/api/products/rum',
                    '/api/products/kawa'
                ]
            ]
        ]);

        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'drink3',
                'description' => 'description',
                'preparation' => 'preparation',
                'image' => '../images',
                'categories' => [
                    '/api/categories/słodki',
                    '/api/categories/kwaśny'
                ],
                'products' => [
                    '/api/products/wino%20białe',
                    '/api/products/mięta',
                    '/api/products/kawa'
                ]
            ]
        ]);

        $this->client->request(
            'GET',
            '/api/drinks?products[]=wódka&products[]=sok%20wiśniowy&categories=słodki'
        );
        //Assertions
        $this->assertJsonContains([
            '@context' => '/api/contexts/Drink',
            '@id' => '/api/drinks',
            '@type' => 'hydra:Collection',
            'hydra:member' => [
                [
                    '@type' => 'Drink',
                    'name' => 'drink2',
                ],
            ]
        ]);

        $this->client->request(
            'GET',
            '/api/drinks?products[]=mięta&categories=orzeźwiający'
        );
        //Assertions
        $this->assertJsonContains([
            '@context' => '/api/contexts/Drink',
            '@id' => '/api/drinks',
            '@type' => 'hydra:Collection',
            'hydra:member' => [
                [
                    '@type' => 'Drink',
                    'name' => 'drink1',
                ],
            ]
        ]);

        $this->client->request(
            'GET',
            '/api/drinks?products[]=kawa&categories[]=słodki&categories[]=kwaśny'
        );
        //Assertions
        $this->assertJsonContains([
            '@context' => '/api/contexts/Drink',
            '@id' => '/api/drinks',
            '@type' => 'hydra:Collection',
            'hydra:member' => [
                [
                    '@type' => 'Drink',
                    'name' => 'drink2',
                ],
                [
                    '@type' => 'Drink',
                    'name' => 'drink3',
                ],
            ]
        ]);
    }

    public function test_return_empty_collection_when_there_is_no_match_in_filter(): void
    {
        $productFixture = new ProductFixtures();
        $categoryFixture = new CategoryFixtures();
        $productFixture->load($this->entityManager);
        $categoryFixture->load($this->entityManager);

        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'drink1',
                'description' => 'description',
                'preparation' => 'preparation',
                'image' => '../images',
                'categories' => [
                    '/api/categories/orzeźwiający'
                ],
                'products' => [
                    '/api/products/wódka',
                    '/api/products/sok%20wiśniowy',
                    '/api/products/mięta'
                ]
            ]
        ]);

        $this->client->request('GET', '/api/drinks?products=rum');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Drink',
            '@id' => '/api/drinks',
            '@type' => 'hydra:Collection',
            'hydra:member' => [],
            'hydra:totalItems' => 0
        ]);

        $this->client->request('GET', '/api/drinks?products=wódka&categories=mocny');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Drink',
            '@id' => '/api/drinks',
            '@type' => 'hydra:Collection',
            'hydra:member' => [],
            'hydra:totalItems' => 0
        ]);
    }
}
