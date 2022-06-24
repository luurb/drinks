<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $products = [
            'biały rum',
            'wódka',
            'szampan',
            'wino czerwone',
            'wino białe',
            'piwo',
            'whiskey',
            'rum',
            'sok jabłkowy',
            'sok wiśniowy',
            'sok bananowy',
            'sok ananasowy',
            'sok grejpfrutowy',
            'syrop cukrowy',
            'lód',
            'mięta',
            'cukier',
            'kawa',
        ];

        foreach ($products as $productName) {
            $product = new Product();
            $product->setName($productName);
            $productEntities[] = $product;
            
            $manager->persist($product);
        }

        $manager->flush();
    }
}
