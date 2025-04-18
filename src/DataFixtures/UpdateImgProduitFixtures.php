<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UpdateImgProduitFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $repProduit = $manager->getRepository(Produit::class);
        $listeProduits = $repProduit->findAll();

        foreach ($listeProduits as $monProduit) {
            switch ($monProduit->getNom()) {
                case 'imprimantes':
                    $monProduit->setlien_Image("imprimantes.jpg");
                    break;
                case 'cartouches encre':
                    $monProduit->setlien_Image("cartouches.jpg");
                    break;
                case 'ordinateurs':
                    $monProduit->setlien_Image("ordinateurs.jpg");
                    break;
                case 'écrans':
                    $monProduit->setlien_Image("ecrans.jpg");
                    break;
                case 'claviers':
                    $monProduit->setlien_Image("claviers.jpg");
                    break;
                case 'souris':
                    $monProduit->setlien_Image("souris.jpg");
                    break;
            }
            $manager->persist($monProduit);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}
