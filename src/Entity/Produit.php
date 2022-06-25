<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"type",type: "string")]
#[ORM\DiscriminatorMap(["burger" => "Burger","complement" => "Complement","menu"=>"Menu"])]
#[ApiResource(
    //redefinition des ressources
    collectionOperations:[
        "get" =>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' =>['groups' => ['burger:read:simple']],
        ],

        "post"],
    itemOperations:["put","get"]
)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["burger:read:simple"])]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[Groups(["burger:read:simple"])]
    #[ORM\Column(type: 'string', length: 100)]
    protected $nom;

    #[Groups(["burger:read:simple"])]
    #[ORM\Column(type: 'float')]
    protected $prix;

    #[ORM\Column(type: 'smallint',options:["default"=>1])]
    protected $etat;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'produits')]
    private $user;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;


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

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

}
