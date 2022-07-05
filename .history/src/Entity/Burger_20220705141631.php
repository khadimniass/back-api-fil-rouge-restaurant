<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BurgerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
#[ApiResource(
    c
)]
class Burger extends Produit
{
    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'burgers')]
    private $menu;

    #[ORM\ManyToMany(targetEntity: Boisson::class, mappedBy: 'burgers')]
    private $boissons;

    #[ORM\ManyToMany(targetEntity: Frite::class, mappedBy: 'burgers')]
    private $frites;

    public function __construct()
    {
        parent::__construct();
        $this->boissons = new ArrayCollection();
        $this->frites = new ArrayCollection();
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

    /**
     * @return Collection<int, Boisson>
     */
    public function getBoissons(): Collection
    {
        return $this->boissons;
    }

    public function addBoisson(Boisson $boisson): self
    {
        if (!$this->boissons->contains($boisson)) {
            $this->boissons[] = $boisson;
            $boisson->addBurger($this);
        }

        return $this;
    }

    public function removeBoisson(Boisson $boisson): self
    {
        if ($this->boissons->removeElement($boisson)) {
            $boisson->removeBurger($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Frite>
     */
    public function getFrites(): Collection
    {
        return $this->frites;
    }

    public function addFrite(Frite $frite): self
    {
        if (!$this->frites->contains($frite)) {
            $this->frites[] = $frite;
            $frite->addBurger($this);
        }

        return $this;
    }

    public function removeFrite(Frite $frite): self
    {
        if ($this->frites->removeElement($frite)) {
            $frite->removeBurger($this);
        }

        return $this;
    }
}
