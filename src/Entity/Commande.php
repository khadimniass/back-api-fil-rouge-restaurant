<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ApiResource(
    itemOperations:[
        "get"=>[
            'status' => Response::HTTP_OK,
            "normalization_context" => ['groups' => ['get:detail:commande']],
        ],
        "put"=>[
            'normalization_context' => ['groups' => ['put:detail:commande']]
        ],
        "PATCH"
    ],
    collectionOperations:[
        "post"=>[
            "denormalization_context"=>['groups'=>['view:commandes']]
        ],
        "get"=>[
            "normalization_context" => ['groups' => ['get:view:commande']],
        ]
    ]
)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups([
        'get:view:commande','put:detail:commande',
        'get:detail:commande','get:detail:user',
        'user:read:simple','get:detail:livreur',
        'get:detail:livraison'
        ])]
    private $id;

    #[ORM\Column(type: 'string', length : 50)]
    #[Groups(['get:view:commande',
        'get:detail:commande','get:detail:user',
        'user:read:simple','get:detail:livreur','get:detail:livraison'])]
    private $etat;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['get:view:commande','get:detail:commande','get:detail:user',
        'get:detail:livreur','get:detail:livraison'])]
    private $addedAt;

    #[ORM\ManyToOne(targetEntity: Livraison::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['get:detail:user'])]
    private $livraison;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: LigneCommande::class,cascade:['persist'] )]
    #[SerializedName('Produits')]
    #[Groups(['view:commandes','get:detail:commande','get:detail:user'])]
    private $ligneCommandes;

    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'commandes')]
    #[Groups([
        'view:commandes','get:view:commande',
        'get:detail:commande','get:detail:user',
        'get:detail:livreur','get:detail:livraison'])]
    private $zone;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get:detail:commande','get:view:commande','get:detail:livreur'])]
    private $user;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Groups(['get:view:commande','get:detail:commande'])]
    private $numeroCommande;

    public function __construct()
    {
        $this->addedAt=new \DateTime();
        $this->etat = "en cours";
        $this->ligneCommandes = new ArrayCollection();
        $this->numeroCommande = "COM".date("YmdHis");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
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

    public function getNumeroCommande(): ?string
    {
        return $this->numeroCommande;
    }

    public function setNumeroCommande(?string $numeroCommande): self
    {
        $this->numeroCommande = $numeroCommande;

        return $this;
    }
}
