<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $parent = $this->createCategory($manager, null, 'Informatiques');
        $this->createCategory($manager, $parent, 'Ordinateurs Portables');
        $this->createCategory($manager, $parent, 'Ecrans');
        $this->createCategory($manager, $parent, 'Souris');

        $parent = $this->createCategory($manager, null, 'Mode');
        $this->createCategory($manager, $parent, 'Homme');
        $this->createCategory($manager, $parent, 'Femme');
        $this->createCategory($manager, $parent, 'Enfant');

        $manager->flush();

    }
    public function createCategory(ObjectManager $manager, Categories $parent = null, string $categoryName)
    {
        $category = new Categories();
        $category->setName($categoryName);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        if ($parent) {
            $category->setParent($parent);
        }
        $manager->persist($category);  //* pour inscrire l'objt category en DB
        return $category;
    }
}
