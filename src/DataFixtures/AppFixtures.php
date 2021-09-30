<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        $tableauImage = ["cafe1.jpg", "cafe2.jpg", "cafe3.jpg", "cafe4.jpg", "cafe5.jpg", "cafe6.jpg", "cafe7.jpg", "cafe8.jpg", "cafe9.jpg", "cafe10.jpg"];

        for ($i = 0; $i < 10; $i++) {
            $produit = new Produit();
            $produit->setDesignation('Café "' . $faker->sentence(3) . '"')
                ->setDescription($faker->text(100))
                ->setPrix($faker->randomFloat(2, 5, 12))
                //->setNomImage($faker->randomElement($tableauImage));
                ->setNomImage($tableauImage[$i]);

            $manager->persist($produit);
        }

        $admin = new User();
        $admin->setPrenom("Emi");
        $admin->setNom("Moi");
        $admin->setEmail("emi@moi.fr");
        $admin->setPassword($this->hasher->hashPassword($admin, "moi"));
        $admin->setRoles(["ROLE_ADMIN"]);

        $manager->persist($admin);

        $user = new User();
        $user->setPrenom("John");
        $user->setNom("Doe");
        $user->setEmail("john@doe.fr");
        $user->setPassword($this->hasher->hashPassword($user, "azerty"));

        $manager->persist($user);

        $manager->flush();
    }
}
