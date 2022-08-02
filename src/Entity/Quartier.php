<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuartierRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: QuartierRepository::class)]
#[ApiResource(
    collectionOperations:[
        "get"=>[
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['get:quartier:read']]
        ],
        "post"=>[
            "denormalization_context"=>['groups'=>['post:view:quartier']]
        ]
    ],
    itemOperations:[
        "get",
    ]
)]
class Quartier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['get:quartier:read','post:view:quartier'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'quartiers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get:quartier:read','post:view:quartier'])]
    private $zone;

    #[ORM\Column(type: 'integer',options: ['default'=>1])]
    #[Groups(['get:quartier:read','post:view:quartier'])]
    private $etat;

    #[ORM\ManyToOne(targetEntity: Gestionnaire::class, inversedBy: 'quartiers')]
    #[ORM\JoinColumn(nullable: false)]
    private $gestionnaire;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    #[Groups(['get:quartier:read','post:view:quartier'])]
    private $nom;

    public function __construct() {
        $this->addedAt= new \DateTime();
        $this->etat=1;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
}
