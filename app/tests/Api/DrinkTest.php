<?php

namespace App\Tests\Api;

use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\ProductFixtures;
use App\Entity\Drink;
use App\Entity\Rating;
use App\Entity\Review;
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
        $user = $this->createUserAndLogIn($this->client, 'test', '12345');
        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => $name,
                'description' => 'test description',
                'preparation' => 'test preparation',
                'image' => '../images',
                'author' => '/api/users/' . $user->getId()
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
            'json' => []
        ]);
        $this->assertResponseStatusCodeSame(401);

        $user = $this->createUserAndLogIn($this->client, 'test2', '12345');
        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'mohito',
                'description' => 'test description',
                'preparation' => 'test preparation',
                'image' => '../images',
                'author' => '/api/users/' . $user->getId()
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            'name' => 'mohito',
            'description' => 'test description',
            'preparation' => 'test preparation',
            'image' => '../images',
            'products' => [],
            'categories' => [],
            'author' => [
                'username' => $user->getUsername()
            ]
        ]);

        //Auto set author
        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'mohito',
                'description' => 'test description',
                'preparation' => 'test preparation',
                'image' => '../images',
            ]
        ]);
        $this->assertResponseIsSuccessful();
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
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'name' => 'update',
        ]);

        $user2 = $this->createUserAndLogIn($this->client, 'test2', '12345');

        //User who is not owner of the drink cant modify it
        $this->client->request('PUT', "/api/drinks/$drinkId", [
            'json' => [
                'name' => 'update',
            ]
        ]);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testPatch(): void
    {
        $this->createDrink('mohito');
        $drinkRecord = $this->entityManager->getRepository(Drink::class)->findOneBy(['name' => 'mohito']);
        $drinkId = $drinkRecord->getId();

        $this->client->request('PATCH', "/api/drinks/$drinkId", [
            'json' => [
                'name' => 'update',
            ],
            'headers' => [
                'content-type' => 'application/merge-patch+json'
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'name' => 'update',
        ]);

        $user2 = $this->createUserAndLogIn($this->client, 'test2', '12345');

        //User who is not owner of the drink cant modify it
        $this->client->request('PATCH', "/api/drinks/$drinkId", [
            'json' => [
                'name' => 'update',
            ],
            'headers' => [
                'content-type' => 'application/merge-patch+json'
            ]
        ]);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testDelete(): void
    {
        $this->createDrink('mohito');

        $drinkRecord = $this->entityManager->getRepository(Drink::class)->findOneBy(['name' => 'mohito']);
        $drinkId = $drinkRecord->getId();

        $this->client->request('DELETE', "/api/drinks/$drinkId");

        $this->assertResponseStatusCodeSame(403);

        $adminUser = $this->createUser('admin', '12345', ['ROLE_ADMIN']);
        $this->logIn($this->client, 'admin', '12345');

        $this->client->request('DELETE', "/api/drinks/$drinkId");

        $this->assertResponseStatusCodeSame(204);
    }

    public function test_drink_returns_products_and_categories_in_format_with_names(): void
    {
        $productFixture = new ProductFixtures();
        $categoryFixture = new CategoryFixtures();
        $productFixture->load($this->entityManager);
        $categoryFixture->load($this->entityManager);
        $user = $this->createUserAndLogIn($this->client, 'test', '12345');

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
                ],
                'author' => '/api/users/' . $user->getId()
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
        $user = $this->createUserAndLogIn($this->client, 'test', '12345');

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
                ],
                'author' => '/api/users/' . $user->getId()
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
                ],
                'author' => '/api/users/' . $user->getId()
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
                ],
                'author' => '/api/users/' . $user->getId()
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
        $user = $this->createUserAndLogIn($this->client, 'test', '12345');

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
                ],
                'author' => '/api/users/' . $user->getId()
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
        $faker = Factory::create();
        $user = $this->createUser('test', '12345');

        for ($i = 0; $i < 25; $i++) {
            $drink = new Drink();
            $drink->setName($faker->word);
            $drink->setDescription('test');
            $drink->setPreparation('test');
            $drink->setImage('test');
            $drink->setAuthor($user);
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
        $drink->setAuthor($this->createUser('test', '12345'));

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
        $drink->setAuthor($this->createUser('test', '12345'));

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

    public function test_admin_can_read_and_write_isPublished_property(): void
    {
        $this->createDrink('mohito');
        $drinkRecord = $this->entityManager->getRepository(Drink::class)->findOneBy(['name' => 'mohito']);
        $drinkId = $drinkRecord->getId();

        $response = $this->client->request('GET', '/api/drinks/' . $drinkId);
        $this->assertNotContains([
            'isPublished' => false
        ], json_decode($response->getContent(), true));

        $this->client->request('PUT', '/api/drinks/' . $drinkId, [
            'json' => [
                'isPublished' => true
            ]
        ]);
        $this->assertJsonContains([
            'isPublished' => false
        ]);

        //Admin user can edit 
        $this->createUser('admin', 'admin', ['ROLE_ADMIN']);
        $this->logIn($this->client, 'admin', 'admin');
        $this->client->request('PUT', '/api/drinks/' . $drinkId, [
            'json' => [
                'isPublished' => true
            ]
        ]);
        $this->client->request('GET', '/api/drinks/' . $drinkId);
        $this->assertJsonContains([
            'isPublished' => true
        ]);
    }

    public function test_api_user_can_set_author_only_to_himself_but_admin_can_to_everyone(): void
    {
        $user = $this->createUserAndLogIn($this->client, 'test', '12345');
        $otherUser = $this->createUser('test2', '12345');
        $adminUser = $this->createUser('admin', 'admin', ['ROLE_ADMIN']);

        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'mohito',
                'description' => 'test',
                'preparation' => 'test',
                'image' => 'test',
                'author' => '/api/users/' . $otherUser->getId()
            ]
        ]);
        $this->assertResponseStatusCodeSame(422, 'Not passing currently logged in user');

        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'mohito1',
                'description' => 'test',
                'preparation' => 'test',
                'image' => 'test',
                'author' => '/api/users/' . $user->getId()
            ]
        ]);
        $this->assertResponseIsSuccessful();

        $this->logIn($this->client, 'admin', 'admin');
        $this->client->request('POST', '/api/drinks', [
            'json' => [
                'name' => 'mohito2',
                'description' => 'test',
                'preparation' => 'test',
                'image' => 'test',
                'author' => '/api/users/' . $adminUser->getId()
            ]
        ]);
        $this->assertResponseIsSuccessful();

    }

    public function test_get_avg_rating_and_number_of_ratings(): void
    {
        $drink = new Drink();
        $drink->setName('mohito');
        $drink->setDescription('description');
        $drink->setPreparation('test');
        $drink->setImage('test');
        $drink->setAuthor($this->createUser('test', '12345'));

        $user1 = $this->createUser('user1', '12345');
        $user2 = $this->createUser('user2', '12345');

        $rating1 = new Rating();
        $rating1->setRating(4);
        $rating1->setDrink($drink);
        $rating1->setUser($user1);

        $rating2 = new Rating();
        $rating2->setRating(2);
        $rating2->setDrink($drink);
        $rating2->setUser($user2);

        $rating3 = new Rating();
        $rating3->setRating(4);
        $rating3->setDrink($drink);
        $rating3->setUser($user2);

        $this->entityManager->persist($drink);
        $this->entityManager->persist($rating1);
        $this->entityManager->persist($rating2);
        $this->entityManager->persist($rating3);
        $this->entityManager->flush();

        $this->client->request('GET', '/api/drinks/' . $drink->getId());
        $this->assertJsonContains([
            'avgRating' => 3.33,
            'ratingsStats' => [
                1 => 0,
                2 => 1,
                3 => 0,
                4 => 2,
                5 => 0
            ],
            'ratingsNumber' => 3
        ]);
    }

    public function test_get_number_of_reviews(): void
    {
        $drink = new Drink();
        $drink->setName('mohito');
        $drink->setDescription('description');
        $drink->setPreparation('test');
        $drink->setImage('test');
        $drink->setAuthor($this->createUser('test', '12345'));

        $user1 = $this->createUser('user1', '12345');
        $user2 = $this->createUser('user2', '12345');

        $review1 = new Review();
        $review1->setDrink($drink);
        $review1->setReview('review');
        $review1->setTitle('title');
        $review1->setAuthor($user1);

        $review2 = new Review();
        $review2->setDrink($drink);
        $review2->setReview('review');
        $review2->setTitle('title');
        $review2->setAuthor($user2);

        $this->entityManager->persist($drink);
        $this->entityManager->persist($review1);
        $this->entityManager->persist($review2);
        $this->entityManager->flush();

        $this->client->request('GET', '/api/drinks/' . $drink->getId());
        $this->assertJsonContains([
            'reviewsNumber' => 2
        ]);
    }
}
