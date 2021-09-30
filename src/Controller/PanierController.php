<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier')]
    public function index(): Response
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    #[Route('/ajout-panier/{id}', name: 'ajout_panier')]
    public function ajoutPanier($id, Request $request): Response
    {

        $session = $request->getSession();

        $panier = $session->get('panier', []);
        // s'il y a déjà cet article dans le panier, on augmente de 1 la quantité
        if (isset($panier[$id])) {
            $panier[$id]++;
        } else { // sinon on ajoute l'id de l'article au panier avec une quantité de 1
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);
        dd($session);

        return $this->render('panier/index.html.twig');
    }
}
