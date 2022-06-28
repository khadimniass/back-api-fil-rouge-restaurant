<?php

namespace App\Entity;

use App\Entity\MenuBurger;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BurgerRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
#[ApiResource(
    //redefinition des ressources
    collectionOperations:[
        "get" =>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' =>['groups' => ['burger:read:simple']],
        ],

        "post"],

    itemOperations:["put","get"]
)]
class Burger extends MenuBurger
{
    #[ORM\ManyToMany(targetEntity: Complement::class, mappedBy: 'burger')]
    private $complements;

    #[ORM\ManyToOne(targetEntity: BoissonFrite::class, inversedBy: 'burgers')]
    #[ORM\JoinColumn(nullable: false)]
    private $boissonFrite;

    public function __construct()
    {
        parent::__construct();
        $this->complements = new ArrayCollection();
    }

    /**
     * @return Collection<int, Complement>
     */
    public function getComplements(): Collection
    {
        return $this->complements;
    }

    public function addComplement(Complement $complement): self
    {
        if (!$this->complements->contains($complement)) {
            $this->complements[] = $complement;
            $complement->addBurger($this);
        }

        return $this;
    }

    public function removeComplement(Complement $complement): self
    {
        if ($this->complements->removeElement($complement)) {
            $complement->removeBurger($this);
        }

        return $this;
    }

    public function getBoissonFrite(): ?BoissonFrite
    {
        return $this->boissonFrite;
    }

    public function setBoissonFrite(?BoissonFrite $boissonFrite): self
    {
        $this->boissonFrite = $boissonFrite;

        return $this;
    }
}
