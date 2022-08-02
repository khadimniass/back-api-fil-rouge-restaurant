<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BurgerRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
#[ApiResource(
    collectionOperations:[
        "get"=>[
            'status' => Response::HTTP_OK,
            "normalization_context"=>['groups' => ['get:view:burger']]
        ],
        "post"=>[
            "denormalization_context"=>['groups'=>['post:view:burger']]
        ]
    ]
)]
class Burger extends Produit
{
    #[ORM\ManyToMany(targetEntity: Frite::class, mappedBy: 'burgers')]
    private $frites;

    #[ORM\OneToMany(mappedBy: 'burger', targetEntity: MenuBurger::class)]
    private $menuBurgers;

    public function __construct()
    {
        parent::__construct();
        $this->frites = new ArrayCollection();
        $this->menuBurgers = new ArrayCollection();
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

    /**
     * @return Collection<int, MenuBurger>
     */
    public function getMenuBurgers(): Collection
    {
        return $this->menuBurgers;
    }

    public function addMenuBurger(MenuBurger $menuBurger): self
    {
        if (!$this->menuBurgers->contains($menuBurger)) {
            $this->menuBurgers[] = $menuBurger;
            $menuBurger->setBurger($this);
        }

        return $this;
    }

    public function removeMenuBurger(MenuBurger $menuBurger): self
    {
        if ($this->menuBurgers->removeElement($menuBurger)) {
            // set the owning side to null (unless already changed)
            if ($menuBurger->getBurgers() === $this) {
                $menuBurger->setBurger(null);
            }
        }
        return $this;
    }
}