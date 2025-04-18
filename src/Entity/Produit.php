<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Callback;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[UniqueEntity(fields: ['nom'], message: 'Ce nom de produit existe déjà.', groups: ['registration'])]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
#[Assert\Length(
    min: 2,
    max: 50,
    minMessage: 'Votre nom doit faire au moins {{ limit }} caractères',
    maxMessage: 'Votre nom ne doit pas dépasser {{ limit }} caractères',
    groups: ['all']
)]
#[Assert\Regex(
    pattern: "/^[A-Za-z]+$/",    
    message: 'Le nom ne doit contenir que des lettres (sans accents, chiffres, espaces ou caractères spéciaux).',
    groups: ['all']
)]
private ?string $nom = null;


    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?bool $rupture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lienImage = null;

    #[ORM\OneToOne(targetEntity: Reference::class, cascade: ['persist'], fetch: 'EAGER')]
    private ?Reference $reference = null;

    #[ORM\ManyToMany(targetEntity: Distributeur::class, cascade: ['persist'])]
    private Collection $distributeurs;

    #[ORM\OneToMany(targetEntity: ProduitDistributeur::class, mappedBy: 'produit')]
    private Collection $produitDistributeurs;

    public function __construct()
    {
        $this->distributeurs = new ArrayCollection();
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

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function isRupture(): ?bool
    {
        return $this->rupture;
    }

    public function setRupture(bool $rupture): self
    {
        $this->rupture = $rupture;

        return $this;
    }

    public function getLienImage(): ?string
    {
        return $this->lienImage;
    }

    public function setLienImage(?string $lienImage): self
    {
        $this->lienImage = $lienImage;

        return $this;
    }

    public function getReference(): ?Reference
    {
        return $this->reference;
    }

    public function setReference(?Reference $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection<int, Distributeur>
     */
    public function getDistributeurs(): Collection
    {
        return $this->distributeurs;
    }

    public function addDistributeur(Distributeur $distributeur): self
    {
        if (!$this->distributeurs->contains($distributeur)) {
            $this->distributeurs->add($distributeur);
        }

        return $this;
    }

    public function removeDistributeur(Distributeur $distributeur): self
    {
        $this->distributeurs->removeElement($distributeur);

        return $this;
    }

    /**
     * @return Collection<int, ProduitDistributeur>
     */
    public function getProduitDistributeurs(): Collection
    {
        return $this->produitDistributeurs;
    }

    public function addProduitDistributeur(ProduitDistributeur $produitDistributeur): self
    {
        if (!$this->produitDistributeurs->contains($produitDistributeur)) {
            $this->produitDistributeurs->add($produitDistributeur);
            $produitDistributeur->setProduit($this);
        }

        return $this;
    }

    public function removeProduitDistributeur(ProduitDistributeur $produitDistributeur): self
    {
        if ($this->produitDistributeurs->removeElement($produitDistributeur)) {
            // set the owning side to null (unless already changed)
            if ($produitDistributeur->getProduit() === $this) {
                $produitDistributeur->setProduit(null);
            }
        }

        return $this;
    }

    #[Assert\Callback]
    public function isContentValid(ExecutionContextInterface $context): void
    {
        $forbiddenWords = ['arme', 'médicament', 'drogue'];

        if (preg_match('#' . implode('|', $forbiddenWords) . '#i', $this->getNom())) {
            $context->buildViolation('Le produit est interdit à la vente')
                ->atPath('nom')
                ->addViolation();
        }
    }

    #[Assert\IsTrue(message: 'Erreur valeurs négatives sur le prix ou la quantité')]
    public function isPrixQuantiteValid(): bool
    {
        if (is_float($this->getPrix()) &&
            (is_int($this->getQuantite()))
            && ($this->getPrix() > 0) && ($this->getQuantite() > 0)) {
            return true;
        } else {
            return false;
        }
    }
}
