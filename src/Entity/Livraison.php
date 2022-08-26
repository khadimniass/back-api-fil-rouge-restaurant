<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivraisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
#[ApiResource(
    collectionOperations: [
        "GET",
        "POST"=>[
            // "security"=>"is_granted('ROLE_GESTIONNAIRE')",
            // "security_message"=>"vous etes pas autorisée a effectuer cette action",
            "denormalization_context"=>['groups'=>['post:livraison:view']]
        ]
    ],
    itemOperations: [
        "GET"=>[
            'status' => Response::HTTP_OK,
            "normalization_context" => ['groups' => ['get:detail:livraison']]
        ],
        "DELETE"
    ]
)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:read:simple','get:detail:livreur','get:detail:livraison','user:read:simple'])]
    private ?int $id;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['user:read:simple','get:detail:livreur','get:detail:livraison','user:read:simple'])]
    private $date;

    #[ORM\ManyToOne(targetEntity: Livreur::class, inversedBy: 'livraisons')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:livraison:view','get:detail:livraison'])]
    private $livreur;

    #[ORM\OneToMany(mappedBy: 'livraison', targetEntity: Commande::class)]
    #[Groups(['post:livraison:view',
        'user:read:simple','get:detail:livreur',
        'get:detail:livraison'])]
    private $commandes;

    #[ORM\Column(type: 'integer',options:["default"=>1])]
    #[Groups(['user:read:simple','get:detail:livreur','user:read:simple'])]
    private $etat;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['get:detail:livreur','user:read:simple'])]
    private $prix;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->date = new \DateTime();
        $this->etat=1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLivreur(): ?Livreur
    {
        return $this->livreur;
    }

    public function setLivreur(?Livreur $livreur): self
    {
        $this->livreur = $livreur;
        return $this;
    }
    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setLivraison($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getLivraison() === $this) {
                $commande->setLivraison(null);
            }
        }

        return $this;
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

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
