<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier')]
    public function index(Request $request, ProduitRepository $repo): Response
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);

        $detailPanier = [];
        $total = 0;
        $nbProduit = 0;

        foreach ($panier as $idProduit => $quantite) {
            $nbProduit += $quantite;
            $produit = $repo->find($idProduit);
            $total += $produit->getPrix() * $quantite;

            $detailPanier[] = [
                'produit' => $produit,
                'quantite' => $quantite,
            ];
        }


        return $this->render('panier/index.html.twig', [
            'detailPanier' => $detailPanier,
            'total' => $total,
            'nbProduit' => $nbProduit,
        ]);
    }

    #[Route('/ajout-panier/{id}', name: 'ajout_panier')]
    public function ajoutPanier($id, Request $request): Response
    {
        // récupérer la session (pour récupérer le panier)
        $session = $request->getSession();
        // on récupère le panier et s'il n'existe pas : tableau vide
        $panier = $session->get('panier', []);
        // s'il y a déjà cet article dans le panier, on augmente de 1 la quantité
        if (isset($panier[$id])) {
            $panier[$id]++;
        } else { // sinon on ajoute l'id de l'article au panier avec une quantité de 1
            $panier[$id] = 1;
        }
        // on obtient par exemple un tableau de ce genre 42=>5, 18=>4, 33=>5]
        // (ex : l'article avec l'id 42 a une quantité de 5 dans le panier)

        // on sauvegarde le panier dans la session
        $session->set('panier', $panier);
        // dd($panier);

        return $this->redirect('/panier');
    }
}
