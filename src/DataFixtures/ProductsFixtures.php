<?php

namespace App\DataFixtures;

use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductsFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {
    }
    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');
        for ($prod=1; $prod <=10 ; $prod++) {
            $product = new Products();
            $product->setName($faker->text(10));
            $product->setDescription($faker->text());
            $product->setSlug($this->slugger->slug(strtolower($product->getName())));
            $product->setPrice($faker->numberBetween(150, 5000));
            $product->setStock($faker->numberBetween(0, 20));
            //On va chercher une référence de catégorie
            $category = $this->getReference('cat-'. rand(1, 8));
            $product->setCategories($category);
            $this->setReference('prod-'.$prod, $product);

            $manager->persist($product);
        }


        $manager->flush();
    }
}
