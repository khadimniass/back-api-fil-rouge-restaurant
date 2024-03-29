<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"role",type: "string")]
#[ORM\DiscriminatorMap(["menu" => "Menu","burger" => "Burger","boisson"=>"Boisson","frite"=>"Frite"])]
#[ApiResource(
    collectionOperations: [
        "get",
        "post"=>[
            "security"=>"is_granted('ROLE_GESTIONNAIRE')",
            "security_message"=>"acction non autorisée"
        ]
    ],itemOperations: [
        "GET"=>[
            "status"=>Response::HTTP_OK,
            'normalization_context' => ['groups' => ['get:produit:detail','get:manu:detail']]
        ]
   ]
)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups([
        'get:read_catalogue',
        'get:manu_read','get:manu:detail',
        'get:produit:detail',
        'get:taille:boisson','get:taille:to:boisson:detail',
        'get:detail:commande','get:detail:user',
        'get:view:frite','get:view:boisson','get:detail:taille'
        ])]
    protected $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(['post:view:burger','get:view:burger',
        'post:view:boisson','post:view:frite',
        'get:read_catalogue','get:read_catalogue',
        'get:manu_read','get:manu:detail',
        'get:produit:detail','get:taille:to:boisson:detail',
        'get:detail:commande','get:detail:user',
        'get:view:frite','get:view:boisson'
        ])]
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
    public function setImageBinary($imageBinary) : void
    {
        $this->imageBinary = $imageBinary;
    }
    #[Groups(['get:read_catalogue','get:read_catalogue',
        'get:manu_read','get:manu:detail',
        'get:produit:detail','get:view:frite','get:view:boisson'
    ])]
    #[ORM\Column(type: 'integer',options: ['default'=>1])]
    protected $etat;

    #[ORM\Column(type: 'text')]
    #[Groups(['post:view:burger','get:view:burger',
        'get:manu_read','post:view:boisson',
        'post:view:frite','get:read_catalogue','get:read_catalogue',
        'get:manu_read','get:manu:detail','get:produit:detail','get:taille:boisson',
        'get:taille:to:boisson:detail','get:view:frite','get:view:boisson'
        ])]
    protected $description;

    #[ORM\Column(type: 'blob', nullable: true)]
    #[Groups(['get:manu_read','get:view:burger',
        'get:read_catalogue',
        'get:manu:detail','get:produit:detail',
        'get:taille:boisson','get:taille:to:boisson:detail',
        'get:detail:commande','get:detail:user',
        'get:view:frite','get:view:boisson'
        ])]
    protected $image; //plainPassword

    #[SerializedName("image")]
    #[Groups(['post:view:burger','post:view:boisson','post:view:frite'])]
    protected $imageBinary; //chemin qui mene vers l'image

    #[ORM\ManyToOne(targetEntity: Gestionnaire::class, inversedBy: 'produits')]
    #[Groups(['get:read_catalogue','get:read_catalogue'])]
    private $gestionnaire;

    #[Groups(['get:read_catalogue','post:view:burger',
        'get:view:burger','get:manu_read',
        'post:view:boisson','post:view:frite',
        'get:read_catalogue','get:produit:detail',
        'get:manu:detail','get:detail:commande',
        'get:detail:user','get:view:frite'
    ])]
    #[ORM\Column(type: 'float', nullable: true)]
    protected $prix;

    #[ORM\Column(type: 'integer')]
    #[Groups(['get:view:burger','get:read_catalogue',
        'get:read_catalogue','get:manu_read',
        'get:manu:detail','get:produit:detail',
        'get:taille:to:boisson:detail',
        'get:view:frite'
    ])]
    protected $quantity;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: LigneCommande::class, cascade: ["persist"])]
    protected $ligneCommandes;

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
        return is_resource($this->image) ?
            utf8_encode(base64_encode(stream_get_contents(($this->image))))
            : $this->image;
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
