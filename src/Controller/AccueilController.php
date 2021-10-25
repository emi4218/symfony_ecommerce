<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\SlideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(ProduitRepository $repo, SlideRepository $slideRepo): Response
    {
        $listeProduits = $repo->findAllJoinLibelle();
        $listeImages = $slideRepo->findAll();


        return $this->render('accueil/index.html.twig', [
            "listeProduits" => $listeProduits,
            "listeImages" => $listeImages,

        ]);
    }
}
