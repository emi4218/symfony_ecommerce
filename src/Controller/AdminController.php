<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Slide;
use App\Repository\ProduitRepository;
use App\Repository\SlideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(ProduitRepository $repo, SlideRepository $repoSlide): Response
    {
        $listeProduit = $repo->findAll();
        $listeImages = $repoSlide->findAll();

        return $this->render('admin/index.html.twig', [
            'listeProduit' => $listeProduit,
            'listeImages' => $listeImages,
        ]);
    }


    // méthode permettant de supprimer un produit

    #[Route('/admin/suppression-produit/{id}', name: 'suppression_produit')]
    public function suppressionProduit($id, ProduitRepository $repo, EntityManagerInterface $manager): Response
    {
        // ici on créé un objet possédant le bon id que le manager comprenne ce qu'il doit supprimer
        // (par exemple une ligne de la table ayant une clef primaire "$id")
        $produit = $manager->getReference('App\\Entity\Produit', $id);
        $manager->remove($produit);
        $manager->flush();

        // $produit = $repo->find($id);
        // $manager->remove($produit);
        // $manager->flush();

        return $this->redirect('/admin');
    }


    #[Route('/admin/creation-produit', name: 'creation_produit')]
    #[Route('/admin/modification-produit/{id}', name: 'modification_produit')]
    public function modificationProduit(Produit $produit = null, Request $request, EntityManagerInterface $manager): Response
    {
        if ($produit == null) {
            $produit = new Produit();
        }

        $formulaire = $this->createFormBuilder($produit)
            ->add('designation', TextType::class, [
                'label' => 'Désignation',
                'attr' => [
                    'placeholder' => 'Nom du produit',
                    'class' => 'form-control'
                ],
                'row_attr' => [
                    'class' => 'form-group', // ne change rien, c'est pour montrer comment mettre une classe sur la classe supérieure
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description du produit',
                    'class' => 'form-control'
                ],
                'row_attr' => [
                    'class' => 'form-group', // ne change rien, c'est pour montrer comment mettre une classe sur la classe supérieure
                ],
            ])
            ->add('prix', null, [
                'attr' => [
                    'placeholder' => 'Prix TTC du produit',
                    'class' => 'form-control'
                ],
                'row_attr' => [
                    'class' => 'form-group', // ne change rien, c'est pour montrer comment mettre une classe sur la classe supérieure
                ],
            ])
            ->add('quantite', null, [
                'attr' => [
                    'placeholder' => 'Quantité de produit',
                    'class' => 'form-control'
                ],
                'row_attr' => [
                    'class' => 'form-group', // ne change rien, c'est pour montrer comment mettre une classe sur la classe supérieure
                ],
            ])
            ->add('nomImage', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new File(
                        [
                            'mimeTypes' => ['image/jpeg', 'image/png'],
                            'mimeTypesMessage' => "Format jpg ou png uniquement"
                        ]
                    )
                ]
            ])
            ->add('Save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-success'
                ],
            ])
            ->getForm();

        // on récupère les données de la requête (du formulaire) et on les affecte au formulaire (et donc à $produit)
        $formulaire->handleRequest($request);

        // si l'utilisateur a cliqué sur le bouton enregistrer
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            // on récupère l'image qui a été choisie par l'utilisateur
            $image = $formulaire->get('nomImage')->getData();

            // si l'utilisateur a sélectionné un fichier
            if ($image) {
                $nomOriginal = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                //$nomUnique = $nomOriginal . '-' . time(); pour le nbe de secondes depuis le 1er janvier 1970
                $nomUnique = $nomOriginal . '-' . uniqid() . '.' . $image->guessExtension(); // nom image modifié par un id unique
                $image->move("uploads", $nomUnique);

                $produit->setNomImage($nomUnique);
            }

            $manager->persist($produit);
            $manager->flush();
            return $this->redirect('/admin');
        }

        $vueFormulaire = $formulaire->createView();

        return $this->render('admin/modification-produit.html.twig', [
            'produit' => $produit,
            'vueFormulaire' => $vueFormulaire,
        ]);
    }


    #[Route('/admin/edition-carrousel/{id}', name: 'edition_carrousel')]
    public function editionCarrousel(Slide $slide, Request $request, EntityManagerInterface $manager): Response
    {

        $formulaire = $this->createFormBuilder($slide)
            ->add('titre', TextType::class, [
                'label' => 'Titre image',
                'attr' => [
                    'placeholder' => 'Titre à voir sur l\'image',
                    'class' => 'form-control'
                ],
            ])

            ->add('texte', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description du produit',
                    'class' => 'form-control'
                ],
            ])

            ->add('nomImage', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new File([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => "Format jpg ou png uniquement"
                    ])
                ]
            ])

            ->add('Save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-success'
                ],
            ])
            ->getForm();

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $image = $formulaire->get('nomImage')->getData();

            // si l'utilisateur a sélectionné un fichier
            if ($image) {
                $nomOriginal = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $nomUnique = $nomOriginal . '-' . uniqid() . '.' . $image->guessExtension();
                $image->move("uploads", $nomUnique);

                $slide->setNomImage($nomUnique);
            }

            $manager->persist($slide);
            $manager->flush();
            return $this->redirect('/admin');
        }

        $vueFormulaire = $formulaire->createView();

        return $this->render('admin/edition-carrousel.html.twig', [
            'slide' => $slide,
            'vueFormulaire' => $vueFormulaire,
        ]);
    }
}
