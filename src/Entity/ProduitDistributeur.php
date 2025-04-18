<?php

namespace App\Entity;

use App\Repository\ProduitDistributeurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitDistributeurRepository::class)]
class ProduitDistributeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $nbProduit;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'produitDistributeurs')]
    #[ORM\JoinColumn(nullable: false)]
    private Produit $produit;

    #[ORM\ManyToOne(targetEntity: Distributeur::class, inversedBy: 'produitDistributeurs')]
    #[ORM\JoinColumn(nullable: false)]
    private Distributeur $distributeur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbProduit(): ?int
    {
        return $this->nbProduit;
    }

    public function setNbProduit(int $nbProduit): static
    {
        $this->nbProduit = $nbProduit;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getDistributeur(): ?Distributeur
    {
        return $this->distributeur;
    }

    public function setDistributeur(?Distributeur $distributeur): static
    {
        $this->distributeur = $distributeur;

        return $this;
    }
}
