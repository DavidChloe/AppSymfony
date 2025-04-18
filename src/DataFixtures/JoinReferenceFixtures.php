<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\Reference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class JoinReferenceFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $repProduit = $manager->getRepository(Produit::class);
        $listeProduits = $repProduit->findAll();

        foreach ($listeProduits as $monProduit) {
            $reference = new Reference;
            $reference->setNumero(rand());
            $monProduit->setReference($reference);
            $manager->persist($monProduit);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group2'];
    }
}
