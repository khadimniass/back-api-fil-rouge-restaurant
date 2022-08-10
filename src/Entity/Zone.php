<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ZoneRepository::class)]
#[ApiResource(
    collectionOperations: [
        "post"=>[
            "denormalization_context"=>['groups'=>['post:view:zone']]
        ],
        "get"=>[
        'status' => Response::HTTP_OK,
        'normalization_context' => ['groups' => ['get:zone:read']]
    ]
    ],
    itemOperations: [
        "get"
    ]
)]
class Zone
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['get:zone:read','post:view:zone','get:detail:user'])]
    private $id;

    #[ORM\Column(type: 'string', length: 150)]
    #[Groups(['get:zone:read','post:view:zone',
        'get:view:commande','get:detail:commande',
        'get:detail:user'
    ])]
    private $nom;

    #[ORM\OneToMany(mappedBy: 'zone', targetEntity: Quartier::class)]
    #[Groups(['get:zone:read','post:view:zone'])]
    private $quartiers;

    #[ORM\Column(type: 'integer',options: ['default'=>1])]
    #[Groups(['get:zone:read','post:view:zone'])]
    private $etat;

    #[ORM\ManyToOne(targetEntity: Gestionnaire::class, inversedBy: 'zones')]
    #[ORM\JoinColumn(nullable: false)]
    private $gestionnaire;

    #[ORM\Column(type: 'integer',nullable:true)]
    #[Groups(['get:zone:read','post:view:zone','get:view:commande','get:detail:user'])]
    private $prixLivraison;

    #[ORM\OneToMany(mappedBy: 'zone', targetEntity: Commande::class)]
    private $commandes;

    public function __construct()
    {
        $this->quartiers = new ArrayCollection();
        $this->etat=1;
        $this->commandes = new ArrayCollection();
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
     * @return Collection<int, Quartier>
     */
    public function getQuartiers(): Collection
    {
        return $this->quartiers;
    }

    public function addQuartier(Quartier $quartier): self
    {
        if (!$this->quartiers->contains($quartier)) {
            $this->quartiers[] = $quartier;
            $quartier->setZone($this);
        }

        return $this;
    }

    public function removeQuartier(Quartier $quartier): self
    {
        if ($this->quartiers->removeElement($quartier)) {
            // set the owning side to null (unless already changed)
            if ($quartier->getZone() === $this) {
                $quartier->setZone(null);
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

    public function getGestionnaire(): ?Gestionnaire
    {
        return $this->gestionnaire;
    }

    public function setGestionnaire(?Gestionnaire $gestionnaire): self
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    public function getPrixLivraison(): ?int
    {
        return $this->prixLivraison;
    }

    public function setPrixLivraison(int $prixLivraison): self
    {
        $this->prixLivraison = $prixLivraison;

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
            $commande->setZone($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getZone() === $this) {
                $commande->setZone(null);
            }
        }

        return $this;
    }
}
