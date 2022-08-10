<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuBoissonRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: MenuBoissonRepository::class)]
#[ApiResource(
    itemOperations: [
        "get"=>[
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['get:taille:to:boisson:detail']]
        ]
    ],
    collectionOperations: [
        "get","post"
    ]
)]
class MenuBoisson       //taille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['post:view:menu','get:produit:detail','get:manu:detail','get:detail:taille','get:taille:to:boisson:detail'])]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['get:produit:detail','get:manu:detail','get:detail:taille','get:taille:to:boisson:detail','get:detail:taille'])]
    private $nom;

    #[Groups(['get:taille:to:boisson:detail'])]
    #[ORM\OneToMany(mappedBy: 'taille', targetEntity: MenuTaille::class)]
    private $menuTailles;

    #[ORM\OneToMany(mappedBy: 'menuBoisson', targetEntity: TailleBoisson::class)]
    #[Groups(['get:manu:detail','get:produit:detail','get:detail:taille','get:taille:to:boisson:detail'])]

    private $tailleBoissons;

    public function __construct()
    {
  //      $this->tailleBoissons = new ArrayCollection();
  $this->menuTailles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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
            $menuTaille->setTaille($this);
        }

        return $this;
    }

    public function removeMenuTaille(MenuTaille $menuTaille): self
    {
        if ($this->menuTailles->removeElement($menuTaille)) {
            // set the owning side to null (unless already changed)
            if ($menuTaille->getTaille() === $this) {
                $menuTaille->setTaille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TailleBoisson>
     */
    public function getTailleBoissons(): Collection
    {
        return $this->tailleBoissons;
    }

    public function addTailleBoisson(TailleBoisson $tailleBoisson): self
    {
        if (!$this->tailleBoissons->contains($tailleBoisson)) {
            $this->tailleBoissons[] = $tailleBoisson;
            $tailleBoisson->setMenuBoisson($this);
        }

        return $this;
    }

    public function removeTailleBoisson(TailleBoisson $tailleBoisson): self
    {
        if ($this->tailleBoissons->removeElement($tailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($tailleBoisson->getMenuBoisson() === $this) {
                $tailleBoisson->setMenuBoisson(null);
            }
        }

        return $this;
    }
}
