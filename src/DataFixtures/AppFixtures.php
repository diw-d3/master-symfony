<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 100; ++$i) {
            $product = new Product();
            $product->setName($faker->text(5).$i);
            $product->setSlug('slug-'.$i);
            $product->setDescription($faker->text);
            $product->setPrice($faker->numberBetween(10, 1000) * 100);
            $product->setUser($this->getReference('user-'.rand(1, 10)));
            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
