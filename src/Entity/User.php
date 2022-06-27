<?php

namespace App\Entity;

use App\Controller\MailController;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"role",type: "string")]
#[ORM\DiscriminatorMap(["client" => "Client","gestionnaire" => "Gestionnaire","livreur"=>"Livreur"])]
#[ApiResource(
    collectionOperations:[
        "get" =>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' =>['groups' => ['user:read:simple']],
        ],
        "post_register" => [
            "method"=>"post",
            'status' => Response::HTTP_CREATED,
            'path'=>'register/',
            'denormalization_context' => ['groups' => ['user:write']],
            'normalization_context' => ['groups' => ['user:read:simple']]
        ]
    ],
    itemOperations:[
        "patch"=>[
            "method"=>"patch",
            "deserialize"=>true,
            "path"=>"/users/validate/{token}",
            "controller"=>MailController::class
        ]
    ])]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['user:read:simple'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[Groups(['user:read:simple'])]
    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(message: "le nom doit pas etre null")]
    protected $nom;

    #[Groups(['user:read:simple'])]
    #[ORM\Column(type: 'string', length: 100)]
    protected $prenom;
    
    #[Groups(['user:read:simple'])]
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    protected $telephone;

    #[ORM\Column(type: 'json')]
    protected $roles = [];

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    protected $login;

    #[ORM\Column(type: 'string')]
    protected $password;

    #[ORM\Column(type: 'smallint', options:["default"=>0])]
    protected $etat;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Produit::class)]
    protected $produits;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected $token;

    #[ORM\Column(type: 'boolean', nullable: true)]
    protected $isEnable;

    #[ORM\Column(type: 'datetime', nullable: true)]
    protected $expireAt;

    // #[ORM\Column(type: 'string', length: 100, nullable: true)]
    #[SerializedName("password")]
    protected $plainPassword;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->expireAt= new \DateTime('+1 day');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_VISITEUR';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

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

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setUser($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getUser() === $this) {
                $produit->setUser(null);
            }
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function isIsEnable(): ?bool
    {
        return $this->isEnable;
    }

    public function setIsEnable(?bool $isEnable): self
    {
        $this->isEnable = $isEnable;

        return $this;
    }

    public function getExpireAt(): ?\DateTime
    {
        return $this->expireAt;
    }

    public function setExpireAt(?\DateTime $expireAt): self
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
    public function tokenGenerator(){
        $this->token=str_replace(['+','/','='],['-','_',''],base64_encode(random_bytes(128)));
    }
    public function arrayRoles(){
        $table=get_called_class();
        $table=explode('\\',$table);
        $table=strtoupper($table[2]);
        $this->roles[]='ROLE_'.$table;
        $this->etat = 0;
    }
}