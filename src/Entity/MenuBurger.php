<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuBurgerRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MenuBurgerRepository::class)]
#[ApiResource]
class MenuBurger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
   // #[Groups(['get:manu:detail','get:produit:detail'])]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups([
        'get:manu_read', 'post:view:menu',
        'get:produit:detail','get:read_catalogue'
    ])]
  //  #[SerializedName('quantite')]
    private $quantiteBurger;

    #[ORM\ManyToOne(targetEntity: Burger::class, inversedBy: 'menuBurgers')]
    #[Assert\NotBlank(message: 'Burger ne doit pas etre null')]
    #[Groups([
        'post:view:menu','get:manu:detail',
        'get:produit:detail','get:read_catalogue'
    ])]
    private $burger;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuBurgers')]
    private $menu;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getQuantiteBurger(): ?int
    {
        return $this->quantiteBurger;
    }

    public function setQuantiteBurger(?int $quantiteBurger): self
    {
        $this->quantiteBurger = $quantiteBurger;
        return $this;
    }

    public function getBurger(): ?Burger
    {
        return $this->burger;
    }

    public function setBurger(?Burger $burger): self
    {
        $this->burger = $burger;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }
}
