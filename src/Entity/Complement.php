<?php

namespace App\Entity;

use App\Repository\ComplementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplementRepository::class)]
class Complement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    private $nom;

    #[ORM\Column(type: 'blob', nullable: true)]
    private $images;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\OneToMany(mappedBy: 'complement', targetEntity: Menu::class)]
    private $menus;

    #[ORM\ManyToMany(targetEntity: Burger::class, inversedBy: 'complements')]
    private $burger;

    #[ORM\ManyToMany(targetEntity: Boisson::class, mappedBy: 'complements')]
    private $boissons;

    #[ORM\ManyToMany(targetEntity: Frite::class, mappedBy: 'complements')]
    private $frites;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
        $this->burger = new ArrayCollection();
        $this->boissons = new ArrayCollection();
        $this->frites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setImages($images): self
    {
        $this->images = $images;

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

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->setComplement($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getComplement() === $this) {
                $menu->setComplement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Burger>
     */
    public function getBurger(): Collection
    {
        return $this->burger;
    }

    public function addBurger(Burger $burger): self
    {
        if (!$this->burger->contains($burger)) {
            $this->burger[] = $burger;
        }

        return $this;
    }

    public function removeBurger(Burger $burger): self
    {
        $this->burger->removeElement($burger);

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
            $boisson->addComplement($this);
        }

        return $this;
    }

    public function removeBoisson(Boisson $boisson): self
    {
        if ($this->boissons->removeElement($boisson)) {
            $boisson->removeComplement($this);
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
            $frite->addComplement($this);
        }

        return $this;
    }

    public function removeFrite(Frite $frite): self
    {
        if ($this->frites->removeElement($frite)) {
            $frite->removeComplement($this);
        }

        return $this;
    }
}
