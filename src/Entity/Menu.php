<?php

namespace App\Entity;

use App\Controller\MenuController;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ApiResource(
    collectionOperations: [
        "post-menu" => [
           "method" => "POST",
           "path" => "menu2",
           "deserialize"=>false,
           "controller" => MenuController::class
        ],

        "POST" => [
     //     "denormalization_context" => ['groups' => ['view:menu']],
      ],
        "GET" => [
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['get:manu_read']]
        ]
    ],
    itemOperations: [
        "get",
        "put",
        "delete"
    ]
)]
class Menu extends Produit
{
    #[Groups(['get:manu_read', 'view:menu'])]
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuFrite::class,cascade:['persist'])]
    #[SerializedName('frites')]
    private $menuFrites;

    #[Groups(['get:manu_read', 'view:menu'])]
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuBoisson::class,cascade:['persist'])]
    #[SerializedName('boissons')]
    private $menuBoissons;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuBurger::class,cascade:['persist'])]
    #[SerializedName('burgers')]
    #[Groups(['get:manu_read', 'view:menu'])]
    private $menuBurgers;

    #[Groups(['view:menu'])]
    protected $nom;
    #[Groups(['view:menu'])]
    protected $description;
    #[Groups(['view:menu'])]
    protected $image;

    public function __construct()
    {
        parent::__construct();
        $this->menuFrites = new ArrayCollection();
        $this->menuBoissons = new ArrayCollection();
        $this->menuBurgers = new ArrayCollection();
    }
    /**
     * @return Collection<int, MenuFrite>
     */
    public function getMenuFrites(): Collection
    {
        return $this->menuFrites;
    }

    public function addMenuFrite(MenuFrite $menuFrite): self
    {
        if (!$this->menuFrites->contains($menuFrite)) {
            $this->menuFrites[] = $menuFrite;
            $menuFrite->setMenu($this);
        }

        return $this;
    }

    public function removeMenuFrite(MenuFrite $menuFrite): self
    {
        if ($this->menuFrites->removeElement($menuFrite)) {
            // set the owning side to null (unless already changed)
            if ($menuFrite->getMenu() === $this) {
                $menuFrite->setMenu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MenuBoisson>
     */
    public function getMenuBoissons(): Collection
    {
        return $this->menuBoissons;
    }

    public function addMenuBoisson(MenuBoisson $menuBoisson): self
    {
        if (!$this->menuBoissons->contains($menuBoisson)) {
            $this->menuBoissons[] = $menuBoisson;
            $menuBoisson->setMenu($this);
        }

        return $this;
    }

    public function removeMenuBoisson(MenuBoisson $menuBoisson): self
    {
        if ($this->menuBoissons->removeElement($menuBoisson)) {
            // set the owning side to null (unless already changed)
            if ($menuBoisson->getMenu() === $this) {
                $menuBoisson->setMenu(null);
            }
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
            $menuBurger->setMenu($this);
        }
        return $this;
    }
    public function removeMenuBurger(MenuBurger $menuBurger): self
    {
        if ($this->menuBurgers->removeElement($menuBurger)) {
            // set the owning side to null (unless already changed)
            if ($menuBurger->getMenu() === $this) {
                $menuBurger->setMenu(null);
            }
        }

        return $this;
    }
}