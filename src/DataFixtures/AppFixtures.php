<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        $tableauImage = ["cafe1.jpg", "cafe2.jpg", "cafe3.jpg", "cafe4.jpg", "cafe5.jpg", "cafe6.jpg", "cafe7.jpg", "cafe8.jpg", "cafe9.jpg", "cafe10.jpg"];

        for ($i = 0; $i < 10; $i++) {
            $produit = new Produit();
            $produit->setDesignation('Café "' . $faker->sentence(3) . '"')
                ->setDescription($faker->text(100))
                ->setPrix($faker->randomFloat(2, 5, 12))
                ->setNomImage($faker->randomElement($tableauImage));
            //->setNomImage($tableauImage[$i]);

            $manager->persist($produit);
        }

        $manager->flush();
    }
}
