<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Store\Product;

class AppFixtures extends Fixture
{
    /** @var ObjectManager */
    private $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadProducts();

        $manager->flush();
    }

    private function loadProducts(): void
    {
        for($i = 1; $i <= 20; $i++)
        {
            $product = new Product();
            $product->setName('Product '.$i);
            $product->setDescription('Produit de description '.$i);
            $product->setPrice(mt_rand(10, 100));
            
            $this->manager->persist($product);
        }
    }
}
