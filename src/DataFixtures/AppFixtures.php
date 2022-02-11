<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Store\Product;
use App\Entity\Store\Image;
use App\Entity\Store\Brand;
use App\Entity\Store\Color;
use App\Entity\Store\Comment;

class AppFixtures extends Fixture
{
    private const DATA_BRANDS = [
        ['Adidas'],
        ['Asics'],
        ['Nike'],
        ['Puma']
    ];

    private const DATA_COLORS = [
        ['Blanc'],
        ['Noir'],
        ['Rouge'],
        ['Bleu'],
        ['Vert'],
        ['Jaune'],
        ['Orange'],
        ['Marron'],
        ['Gris']
    ];

    /** @var ObjectManager */
    private $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadBrands();
        $this->loadColors();
        $this->loadProducts();

        $manager->flush();
    }

    private function loadBrands(): void {
        foreach (self::DATA_BRANDS as $key => [$name]) {
            $brand = new Brand();
            $brand->setName($name);
            $this->manager->persist($brand);
            $this->addReference(Brand::class . $key, $brand);
        }
    }

    private function loadColors(): void {
        foreach (self::DATA_COLORS as $key => [$name]) {
            $color = new Color();
            $color->setName($name);
            $this->manager->persist($color);
            $this->addReference(Color::class . $key, $color);
        }
    }

    private function loadProducts(): void
    {
        for($i = 1; $i <= 20; $i++)
        {
            $product = new Product();
            $product->setName('Product '.$i)
                    ->setBrand($this->getRandomEntityReference(Brand::class, self::DATA_BRANDS))
                    ->setDescription('Produit de description '.$i)
                    ->setSlug('produit-'.$i)
                    ->setPrice(mt_rand(10, 100));
                    
            for ($j = 0; $j < random_int(0, count(self::DATA_COLORS) -1); $j++) {
                if (random_int(0, 1)) {
                    /** @var Color $color */
                    $color = $this->getReference(Color::class . $j);
                    $product->addColor($color);
                }
            }

            for ($k = 1; $k < random_int(2, 5); $k++) {
                /** @var Comment $comment */
                $comment = (new Comment())
                    ->setProduct($product)
                    ->setPseudo('Personne ' . $k)
                    ->setMessage('Voici le message de la personne ' . $k . ' pour la chaussure ' . $i);
                $this->manager->persist($comment);
            }

            $productImage = new Image();
            $productImage
                ->setUrl('shoe-'.$i.'.jpg')
                ->setAlt('Image du produit '.$i);
            
            $product->setImage($productImage);

            
            $this->manager->persist($product);
            sleep(1);
        }
    }

    /**
     * @param class-string $entityClass
     * 
     * @return object<class-string>
     */
    private function getRandomEntityReference(string $entityClass, array $data): object {
        return $this->getReference($entityClass . random_int(0, count($data) - 1));
    }
}
