<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuBoissonRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MenuBoissonRepository::class)]
#[ApiResource]
class MenuBoisson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['view:menu'])]
    #[ORM\Column(type: 'integer')]
    private $quantiteboisson;

    #[ORM\ManyToOne(targetEntity: Boisson::class, inversedBy: 'menuBoissons')]
    #[Groups(['view:menu'])]
    private $boisson;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuBoissons')]
    private $menu;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantiteboisson(): ?int
    {
        return $this->quantiteboisson;
    }

    public function setQuantiteboisson(int $quantiteboisson): self
    {
        $this->quantiteboisson = $quantiteboisson;

        return $this;
    }

    public function getBoisson(): ?Boisson
    {
        return $this->boisson;
    }

    public function setBoisson(?Boisson $boisson): self
    {
        $this->boisson = $boisson;

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
