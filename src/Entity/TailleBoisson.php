<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TailleBoissonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TailleBoissonRepository::class)]
#[ApiResource(
    collectionOperations: [
        "GET" => [

        ],
        "POST"
    ],
    itemOperations: [
        "GET" => [
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['get:taille:boisson']]
        ]
    ]
)]
class TailleBoisson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['get:manu:detail','get:detail:taille',
        'get:taille:boisson','get:view:boisson'])]
    private $id;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['get:manu:detail','get:produit:detail',
        'get:taille:to:boisson:detail','get:view:boisson'
    ])]
    private $prix;

    #[ORM\OneToMany(mappedBy: 'tailleBoisson', targetEntity: LigneCommande::class)]
    private $ligneCommandes;

    #[ORM\Column(type: 'integer')]
    #[Groups(['get:manu:detail','get:produit:detail','get:taille:boisson',
        'get:taille:to:boisson:detail','get:view:boisson'])]
    private $qteBoissonDispo;

    #[ORM\ManyToOne(targetEntity: Boisson::class, inversedBy: 'tailleBoissons')]
    #[Groups(['get:manu:detail','get:produit:detail','get:taille:boisson','get:taille:to:boisson:detail'])]
    private $boisson;

    #[ORM\ManyToOne(targetEntity: MenuBoisson::class, inversedBy: 'tailleBoissons')]
    private $menuBoisson;

    public function __construct()
    {
        $this->ligneCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }
    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    /**
     * @return Collection<int, LigneCommande>
     */
    public function getLigneCommandes(): Collection
    {
        return $this->ligneCommandes;
    }

    public function addLigneCommande(LigneCommande $ligneCommande): self
    {
        if (!$this->ligneCommandes->contains($ligneCommande)) {
            $this->ligneCommandes[] = $ligneCommande;
            $ligneCommande->setTailleBoisson($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): self
    {
        if ($this->ligneCommandes->removeElement($ligneCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommande->getTailleBoisson() === $this) {
                $ligneCommande->setTailleBoisson(null);
            }
        }

        return $this;
    }

    public function getQteBoissonDispo(): ?int
    {
        return $this->qteBoissonDispo;
    }

    public function setQteBoissonDispo(int $qteBoissonDispo): self
    {
        $this->qteBoissonDispo = $qteBoissonDispo;

        return $this;
    }

    public function getBoisson(): ?Boisson
    {
        return $this->boisson;
    }

    public function setBoisson(?Boisson $boisson): self
    {
        $this->boisson = $boisson;

        return $this;
    }

    public function getMenuBoisson(): ?MenuBoisson
    {
        return $this->menuBoisson;
    }

    public function setMenuBoisson(?MenuBoisson $menuBoisson): self
    {
        $this->menuBoisson = $menuBoisson;

        return $this;
    }
}
