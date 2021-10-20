<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\User;
use App\Entity\Slide;
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

        $slide1 = new Slide();
        $slide1->setNomImage("header1.jpg");
        $slide1->setTitre("Le café, c'est la vie");
        $slide1->setTexte("Parce qu'on aime ça");

        $slide2 = new Slide();
        $slide2->setNomImage("header2.jpg");
        $slide2->setTitre("Notre équipe a un grain... mais le bon !!!");
        $slide2->setTexte("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ultricies turpis non massa pretium, ut lacinia urna dignissim. Phasellus commodo vehicula erat, quis cursus metus vulputate a. Fusce et diam sed magna vulputate malesuada vel eget metus.");

        $slide3 = new Slide();
        $slide3->setNomImage("header3.jpg");
        $slide3->setTitre("Drogue légale");
        $slide3->setTexte("En reprenant le titre de la première image, on SAIT que c'est vital");

        $manager->persist($slide1);
        $manager->persist($slide2);
        $manager->persist($slide3);

        $manager->flush();
    }
}
