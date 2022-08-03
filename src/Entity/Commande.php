<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ApiResource(
    itemOperations:[
        "get"
    ],
    collectionOperations:[
        "post"=>[
            "denormalization_context"=>['groups'=>['view:commandes']]
        ],
        "get"
    ]
)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer',options: ['defalt'=>1])]
    private $etat;

    #[ORM\Column(type: 'datetime')]
    private $addedAt;

    #[ORM\ManyToOne(targetEntity: Livraison::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: true)]
    private $livraison;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: LigneCommande::class,cascade:['persist'] )]
    #[SerializedName('Produits')]
    #[Groups(['view:commandes'])]
    private $ligneCommandes;

    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'commandes')]
    #[Groups(['view:commandes'])]
    private $zone;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    public function __construct()
    {
        $this->addedAt=new \DateTime();
        $this->etat = 1;
      $this->ligneCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getAddedAt(): ?\DateTime
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTime $addedAt): self
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        $this->livraison = $livraison;

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
            $ligneCommande->setCommande($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): self
    {
        if ($this->ligneCommandes->removeElement($ligneCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommande->getCommande() === $this) {
                $ligneCommande->setCommande(null);
            }
        }

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
