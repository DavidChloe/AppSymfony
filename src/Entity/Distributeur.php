<?php

namespace App\Entity;

use App\Repository\DistributeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ProduitDistributeur;

#[ORM\Entity(repositoryClass: DistributeurRepository::class)]
class Distributeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, ProduitDistributeur>
     */
    #[ORM\OneToMany(targetEntity: ProduitDistributeur::class, mappedBy: 'distributeur', cascade: ['persist', 'remove'])]
    private Collection $produitDistributeurs;

    public function __construct()
    {
        $this->produitDistributeurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return Collection<int, ProduitDistributeur>
     */
    public function getProduitDistributeurs(): Collection
    {
        return $this->produitDistributeurs;
    }

    public function addProduitDistributeur(ProduitDistributeur $produitDistributeur): static
    {
        if (!$this->produitDistributeurs->contains($produitDistributeur)) {
            $this->produitDistributeurs->add($produitDistributeur);
            $produitDistributeur->setDistributeur($this);
        }
        return $this;
    }

    public function removeProduitDistributeur(ProduitDistributeur $produitDistributeur): static
    {
        if ($this->produitDistributeurs->removeElement($produitDistributeur)) {
            // set the owning side to null (unless already changed)
            if ($produitDistributeur->getDistributeur() === $this) {
                $produitDistributeur->setDistributeur(null);
            }
        }
        return $this;
    }
}
