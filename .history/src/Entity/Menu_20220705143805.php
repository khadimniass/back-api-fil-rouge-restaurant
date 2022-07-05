<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ApiResource(
    collectionOperations: [
        "POST"=>[
        "denormalization_context"=>['groups'=>['view:menu']],
        ] ,
        "GET"=>[
            'status'=> Response::HTTP_OK,
            'normalization_context' => ['groups' => ['get:manu_read'] ]
        ]
        ],
        itemOperations:[
            "get",
            "post"
        ]
)]
class Menu extends Produit
{
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: Burger::class)]
    #[Assert\NotBlank(message: 'on ne peut pas creer un menu sans burgers')]
    private $burgers;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: Frite::class)]
    #[Assert\NotBlank(message: 'on ne peut pas creer un menu sans frites')]
    #[Groups(['view:menu','get:manu_read'])]
    private $frites;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: Boisson::class)]
    #[Groups(['view:menu','get:manu_read',])]
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
