<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 10; ++$i) {
            $category = new Category();
            $category->setName($faker->words(3, true));
            $category->setDescription((bool) rand(0, 1) ? $faker->text : null);
            $category->setSlug($faker->words(3, true));

            $this->addReference('category-'.$i, $category);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
