<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Repository\ProduitRepository; // Import the repository

class ListeProduitsController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function liste(ProduitRepository $produitsRepository): Response
    {
        $listeProduits = $produitsRepository->findAll();
        $lastProduit = $produitsRepository->getLastProduit();

        return $this->render('liste_produits/index.html.twig', [
            'listeproduits' => $listeProduits,
            'lastproduit' => $lastProduit,
        ]);
    }
    #[Route("/eager", name: "eager")]
    public function eager(EntityManagerInterface $entityManager)
    {
        $produitsRepository = $entityManager->getRepository(Produit::class);
        $listeProduits = $produitsRepository->findAll();
        return $this->render('liste_produits/eager.html.twig', [
            'listeproduits' => $listeProduits,
        ]);
    }
}
