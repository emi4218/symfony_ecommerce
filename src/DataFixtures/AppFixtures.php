<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Libelle;
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

        // (Object) sert uniquement à corriger une erreur de vscode
        // $manager = (object) $manager;
        // $manager
        //     ->getConnection()
        //     ->executeQuery("
        // ALTER TABLE produit AUTO_INCREMENT=1;
        // ALTER TABLE categorie AUTO_INCREMENT=1;
        // ALTER TABLE slide AUTO_INCREMENT=1;
        // ALTER TABLE user AUTO_INCREMENT=1;
        // ");

        $faker = Faker\Factory::create();

        $tableauImage = ["cafe1.jpg", "cafe2.jpg", "cafe3.jpg", "cafe4.jpg", "cafe5.jpg", "cafe6.jpg", "cafe7.jpg", "cafe8.jpg", "cafe9.jpg", "cafe10.jpg"];

        //---------- création des catégories -----------

        $categorieCafeEnGrain = new Categorie();
        $categorieCafeEnGrain->setNom("Café en grain");
        $manager->persist($categorieCafeEnGrain);

        $categorieConsommable = new Categorie();
        $categorieConsommable->setNom("Consommable");
        $manager->persist($categorieConsommable);

        $categorieCafeSoluble = new Categorie();
        $categorieCafeSoluble->setNom("Café soluble");
        $manager->persist($categorieCafeSoluble);

        $listeCategories = [
            $categorieCafeEnGrain,
            $categorieConsommable,
            $categorieCafeSoluble,
        ];

        //---------- création des libellés ---------

        $libellePromotion = new Libelle();
        $libellePromotion->setNom('Promo');
        $libellePromotion->setCouleur('FF0000');
        $manager->persist($libellePromotion);

        $libelleEco = new Libelle();
        $libelleEco->setNom('Eco-responsable');
        $libelleEco->setCouleur('57CC57');
        $manager->persist($libelleEco);

        $libelleBestSeller = new Libelle();
        $libelleBestSeller->setNom('Best seller');
        $libelleBestSeller->setCouleur('FF8800');
        $manager->persist($libelleBestSeller);

        $listeDeLibelles = [$libellePromotion, $libelleEco, $libelleBestSeller];

        //---------- création des produits ----------

        for ($i = 0; $i < 50; $i++) {
            $produit = new Produit();
            $produit->setDesignation('Café "' . $faker->sentence(3) . '"')
                ->setDescription($faker->text(1000))
                ->setPrix($faker->randomFloat(2, 5, 12))
                ->setQuantite(5)
                //->setNomImage($faker->randomElement($tableauImage));
                ->setNomImage($faker->randomElement($tableauImage))
                ->setCategorie($faker->randomElement($listeCategories));

            $nombreDeLibelle = $faker->numberBetween(0, 3);

            for ($j = 0; $j < $nombreDeLibelle; $j++) {
                $libellePrisAuHasard = $faker->randomElement($listeDeLibelles);
                $produit->addListeLibelle($libellePrisAuHasard); //addListeLibelle est la fonction que l'on trouve dans Produit.php
            }

            $manager->persist($produit);
        }

        //---------- création des utilisateurs ---------

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


        //---------- création des slides ---------

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
