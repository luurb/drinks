<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\ProductFixtures;
use App\Entity\Drink;
use Faker\Factory;

class DrinkTest extends CustomApiTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
    }

    public function createDrink(string $name): void
    {
        $this->createUserAndLogIn($this->client, 'test', '12345');
        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => $name,
                'description' => 'test description',
                'preparation' => 'test preparation',
                'image' => '../images'
            ]
        ]);
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
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->createUserAndLogIn($this->client, 'test', '12345');
        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'mohito',
                'description' => 'test description',
                'preparation' => 'test preparation',
                'image' => '../images'
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);
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
        $this->createDrink('mohito');
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
        $this->createDrink('mohito');
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
        $this->createDrink('mohito');
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
        $this->createDrink('mohito');

        $drinkRecord = $this->entityManager->getRepository(Drink::class)->findOneBy(['name' => 'mohito']);
        $drinkId = $drinkRecord->getId();

        $this->client->request('DELETE', "/api/drinks/$drinkId");

        $this->assertResponseStatusCodeSame(204);
    }

    public function test_drink_returns_products_and_categories_in_format_with_names(): void
    {
        $productFixture = new ProductFixtures();
        $categoryFixture = new CategoryFixtures();
        $productFixture->load($this->entityManager);
        $categoryFixture->load($this->entityManager);

        $this->createUserAndLogIn($this->client, 'test', '12345');
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

        $this->client->request('GET', '/api/drinks');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Drink',
            '@id' => '/api/drinks',
            '@type' => 'hydra:Collection',
            'hydra:member' => [
                [
                    '@type' => 'Drink',
                    'name' => 'drink1',
                    'description' => 'description',
                    'preparation' => 'preparation',
                    'image' => '../images',
                    'products' => [
                        [
                            '@type' => 'Product',
                            'name' => 'wódka'
                        ],
                        [
                            '@type' => 'Product',
                            'name' => 'sok wiśniowy'
                        ],
                        [
                            '@type' => 'Product',
                            'name' => 'mięta'
                        ],
                    ],
                    'categories' => [
                        [
                            '@type' => 'Category',
                            'name' => 'orzeźwiający'
                        ],
                    ]
                ],
            ]
        ]);
    }

    public function test_return_drinks_which_contain_specific_products_and_categories(): void
    {
        $productFixture = new ProductFixtures();
        $categoryFixture = new CategoryFixtures();
        $productFixture->load($this->entityManager);
        $categoryFixture->load($this->entityManager);

        $this->createUserAndLogIn($this->client, 'test', '12345');
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

        $this->createUserAndLogIn($this->client, 'test', '12345');
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

    public function test_pagination_work_when_its_enabled(): void
    {
        for ($i = 0; $i < 25; $i++) {
            $drink = new Drink();
            $drink->setName('test');
            $drink->setDescription('test');
            $drink->setPreparation('test');
            $drink->setImage('test');
            $this->entityManager->persist($drink);
        }

        $this->entityManager->flush();

        $this->client->request('GET', '/api/drinks');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Drink',
            '@id' => '/api/drinks',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 25,
            'hydra:view' => [
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/drinks?page=1',
                'hydra:last' => '/api/drinks?page=2',
                'hydra:next' => '/api/drinks?page=2'
            ]
        ]);
    }

    public function test_short_description_returns_less_or_equal_180_characters(): void
    {
        $faker = Factory::create();
        $drink = new Drink();
        $drink->setName('test');
        $drink->setDescription($faker->sentence(80));
        $drink->setPreparation('test');
        $drink->setImage('test');

        $this->entityManager->persist($drink);
        $this->entityManager->flush();

        $drinkId = $this->entityManager->getRepository(Drink::class)->findOneBy(['name' => 'test'])->getId();
        $response = $this->client->request('GET', "/api/drinks/$drinkId");
        $response = json_decode($response->getContent(), true);

        //180 + 3 beacuse 3 dots are added to the end of short description
        $this->assertLessThanOrEqual(183, strlen($response['shortDescription']));
    }

    public function test_property_filter_works_correctly(): void
    {
        $drink = new Drink();
        $drink->setName('mohito');
        $drink->setDescription('description');
        $drink->setPreparation('test');
        $drink->setImage('test');

        $this->entityManager->persist($drink);
        $this->entityManager->flush();

        $response = $this->client->request('GET', '/api/drinks?properties[]=shortDescription&properties[]=name');

        $this->assertNotContains([
            'hydra:member' => [
                'description' => 'description',
                'preparation' => 'test',
                'image' => 'test',
            ]
        ], json_decode($response->getContent(), true));

        $this->assertJsonContains([
            'hydra:member' => [
                [
                    'name' => 'mohito',
                    'shortDescription' => 'description',
                ]
            ],
        ]);
    }
}
