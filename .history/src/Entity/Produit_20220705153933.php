<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"role",type: "string")]
#[ORM\DiscriminatorMap(["menu" => "Menu","buger" => "Burger","boisson"=>"Boisson","frite"=>"Frite"])]
#[ApiResource(
    collectionOperations: [
        "get",
        "post"=>[
            "security"=>"is_granted('ROLE_GESTIONNAIRE')",
            "security_message"=>"acction non autorisée"
        ]
    ]
)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(['view:menu','post:view:burger','get:view:burger'])]
    protected $nom;

    /**
     * @return mixed
     */
    public function getImageBinary()
    {
        return $this->imageBinary;
    }

    /**
     * @param mixed $imageBinary
     */
    public function setImageBinary($imageBinary): void
    {
        $this->imageBinary = $imageBinary;
    }

    #[ORM\Column(type: 'integer',options: ['default'=>1])]
    protected $etat;

    #[ORM\Column(type: 'text')]
    #[Groups(['view:menu','post:view:burger','get:view:burger','get:manu_read'])]
    protected $description;

    #[ORM\Column(type: 'blob', nullable: true)] //voir plain password et plainPassword
    protected $image;

    #[SerializedName("image")]
    #[Groups(['post:view:burger','get:view:burger','get:manu_read'])]
    protected $imageBinary;

    #[ORM\ManyToOne(targetEntity: Gestionnaire::class, inversedBy: 'produits')]
    private $gestionnaire;

    #[Groups(['view:menu','post:view:burger','get:view:burger','get:manu_read'])]
    #[ORM\Column(type: 'float', nullable: true)]
    private $prix;

    #[ORM\Column(type: 'integer')]
    #[SerializedName('quantity a stocker')]
#    #[Groups(['get:view:burger'])]
    private $quantity;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: LigneCommande::class, cascade:)]
    private $ligneCommandes;

    public function __construct()
    {
        $this->etat=1;
        $this->ligneCommandes = new ArrayCollection();
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

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
            $ligneCommande->setProduit($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): self
    {
        if ($this->ligneCommandes->removeElement($ligneCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommande->getProduit() === $this) {
                $ligneCommande->setProduit(null);
            }
        }

        return $this;
    }

    }
