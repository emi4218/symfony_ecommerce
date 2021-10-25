<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="categorie")
     */
    private $listeProduits;

    public function __construct()
    {
        $this->listeProduits = new ArrayCollection();
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

    /**
     * @return Collection|Produit[]
     */
    public function getListeProduits(): Collection
    {
        return $this->listeProduits;
    }

    public function addListeProduit(Produit $listeProduit): self
    {
        if (!$this->listeProduits->contains($listeProduit)) {
            $this->listeProduits[] = $listeProduit;
            $listeProduit->setCategorie($this);
        }

        return $this;
    }

    public function removeListeProduit(Produit $listeProduit): self
    {
        if ($this->listeProduits->removeElement($listeProduit)) {
            // set the owning side to null (unless already changed)
            if ($listeProduit->getCategorie() === $this) {
                $listeProduit->setCategorie(null);
            }
        }

        return $this;
    }
}
