<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ApiResource(
    collectionOperations: [
        "POST"=>[
        "denormalization_context"=>['groups'=>['view:menu']],
        ] ,
        "GET"=>[
            'normalization_context' => ['groups' => ['manu_read'] ]
        ]
    ]
)]
class Menu extends Produit
{
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: Burger::class)]
    #[Assert\NotBlank(message: 'on ne peut pas creer un menu sans burgers')]
    private $burgers;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: Frite::class)]
    #[Assert\NotBlank(message: 'on ne peut pas creer un menu sans frites')]
    #[Groups([''])]
    private $frites;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: Boisson::class)]
    private $boissons;

    public function __construct()
    {
        parent::__construct();
        $this->burgers = new ArrayCollection();
        $this->frites = new ArrayCollection();
        $this->boissons = new ArrayCollection();
    }

    /**
     * @return Collection<int, Burger>
     */
    public function getBurgers(): Collection
    {
        return $this->burgers;
    }

    public function addBurger(Burger $burger): self
    {
        if (!$this->burgers->contains($burger)) {
            $this->burgers[] = $burger;
            $burger->setMenu($this);
        }

        return $this;
    }

    public function removeBurger(Burger $burger): self
    {
        if ($this->burgers->removeElement($burger)) {
            // set the owning side to null (unless already changed)
            if ($burger->getMenu() === $this) {
                $burger->setMenu(null);
            }
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
            $frite->setMenu($this);
        }

        return $this;
    }

    public function removeFrite(Frite $frite): self
    {
        if ($this->frites->removeElement($frite)) {
            // set the owning side to null (unless already changed)
            if ($frite->getMenu() === $this) {
                $frite->setMenu(null);
            }
        }

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
            $boisson->setMenu($this);
        }

        return $this;
    }

    public function removeBoisson(Boisson $boisson): self
    {
        if ($this->boissons->removeElement($boisson)) {
            // set the owning side to null (unless already changed)
            if ($boisson->getMenu() === $this) {
                $boisson->setMenu(null);
            }
        }

        return $this;
    }
}
