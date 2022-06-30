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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"role",type: "string")]
#[ORM\DiscriminatorMap(["client" => "Client","gestionnaire" => "Gestionnaire","livreur"=>"Livreur"])]
#[ApiResource(
    collectionOperations:[
        "get",
        "post",
        "VALIDATION" => [
            "method"=>"PATCH",
            'deserialize' => false,
            'path'=>'users/validate/{token}',
            'controller' => MailController::class
        ]

    ],
    itemOperations:[
        "get",
        "put"
    ]
)]

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
  //  #[Assert\Unique(message: 'ce champ doit etre unique')]
    protected $login;

    #[ORM\Column(type: 'string')]
    protected $password;

    #[ORM\Column(type: 'smallint', options:["default"=>0])]
    protected $etat;

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
     * @return Collection<int, Catologue>
     */

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
        $this->token=str_replace(['+','/','='],['-','_',''],base64_encode(random_bytes(16)));
    }
    public function arrayRoles(){
        $table=get_called_class();
        $table=explode('\\',$table);
        $table=strtoupper($table[2]);
        $this->roles[]='ROLE_'.$table;
        $this->etat = 0;
    }
}