<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuFriteRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: MenuFriteRepository::class)]
#[ApiResource]
class MenuFrite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuFrites')]
    private $menu;

    #[ORM\ManyToOne(targetEntity: Frite::class, inversedBy: 'menuFrites')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:view:menu','get:read_catalogue'])]
    private $frite;

    #[ORM\Column(type: 'integer')]
    #[Groups(['post:view:menu','get:read_catalogue'])]
    #[SerializedName('quantite')]
    private $quantitefrite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantitefrite(): ?int
    {
        return $this->quantitefrite;
    }

    public function setQuantitefrite(int $quantitefrite): self
    {
        $this->quantitefrite = $quantitefrite;

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

    public function getFrite(): ?Frite
    {
        return $this->frite;
    }

    public function setFrite(?Frite $frite): self
    {
        $this->frite = $frite;

        return $this;
    }
}
