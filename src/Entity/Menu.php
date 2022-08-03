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
          "denormalization_context" => ['groups' => ['post:view:menu']],
      ],
        "GET" => [
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['get:manu:detail']]
        ]
    ],
    itemOperations: [
        "put",
        "delete",
        "GET" => [
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['get:manu:detail']]
        ]
    ]
)]
class Menu extends Produit
{
    #[Groups(['post:view:menu'])]
    #[SerializedName('nom')]
    protected $nomMenu;
    #[Groups(['post:view:menu'])]
    #[SerializedName('description')]
    protected $descriptionMenu;
    #[Groups(['post:view:menu'])]
    #[SerializedName('image')]
    protected $imageMenu;

    /**
     * @return mixed
     */
    public function getNomMenu()
    {
        return $this->nomMenu;
    }

    /**
     * @param mixed $nomMenu
     */
    public function setNomMenu($nomMenu): void
    {
        $this->nomMenu = $nomMenu;
    }

    /**
     * @return mixed
     */
    public function getDescriptionMenu()
    {
        return $this->descriptionMenu;
    }

    /**
     * @param mixed $descriptionMenu
     */
    public function setDescriptionMenu($descriptionMenu): void
    {
        $this->descriptionMenu = $descriptionMenu;
    }

    /**
     * @return mixed
     */
    public function getImageMenu()
    {
        return $this->imageMenu;
    }

    /**
     * @param mixed $imageMenu
     */
    public function setImageMenu($imageMenu): void
    {
        $this->imageMenu = $imageMenu;
    }

    #[Groups(['get:manu_read', 'post:view:menu','get:manu:detail','get:read_catalogue'])]
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuFrite::class,cascade:['persist'])]
    #[SerializedName('frites')]
    private $menuFrites;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuBurger::class, cascade:['persist'])]
    #[SerializedName('burgers')]
    #[Groups([
        'get:manu_read',
        'post:view:menu','get:manu:detail',
        'get:produit:detail','get:read_catalogue'
    ])]
    private $menuBurgers;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuTaille::class,cascade:['persist'])]
    #[Groups(['post:view:menu','get:manu:detail','get:produit:detail'])]
    private $menuTailles;



    public function __construct()
    {
        parent::__construct();
        $this->menuFrites = new ArrayCollection();
        $this->menuBurgers = new ArrayCollection();
        $this->menuTailles = new ArrayCollection();
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

    /**
     * @return Collection<int, MenuTaille>
     */

    public function getMenuTailles(): Collection
    {
        return $this->menuTailles;
    }
    public function addMenuTaille(MenuTaille $menuTaille): self
    {
        if (!$this->menuTailles->contains($menuTaille)) {
            $this->menuTailles[] = $menuTaille;
            $menuTaille->setMenu($this);
        }

        return $this;
    }

    public function removeMenuTaille(MenuTaille $menuTaille): self
    {
        if ($this->menuTailles->removeElement($menuTaille)) {
            // set the owning side to null (unless already changed)
            if ($menuTaille->getMenu() === $this) {
                $menuTaille->setMenu(null);
            }
        }
        return $this;
    }
}