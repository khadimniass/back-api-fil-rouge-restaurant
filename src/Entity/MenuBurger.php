<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuBurgerRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MenuBurgerRepository::class)]
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
class MenuBurger   //Burger
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

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\ManyToMany(targetEntity: Commande::class, inversedBy: 'menuBurgers')]
    private $commandes;

    public function __construct()
    {
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        $this->commandes->removeElement($commande);

        return $this;
    }

}
