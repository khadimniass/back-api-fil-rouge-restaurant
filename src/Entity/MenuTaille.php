<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MenuTailleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MenuTailleRepository::class)]
#[ApiResource(
    collectionOperations: [
        "POST" => [
            "denormalization_context" => ['groups' => ['post:view:menu_taill']],
        ],
        "GET" => [
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['get:manu_taille']]
        ]
    ],
    itemOperations: [
        "get"=>[
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['get:detail:taille']]
        ],
        "put",
        "delete"
    ]
)]
class MenuTaille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
        #[Groups(['post:view:menu','get:produit:detail','get:detail:taille'])]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true, options: ['default'=>1])]
    private $etat;

    #[ORM\Column(type: 'integer')]
    #[Groups(['post:view:menu','get:taille:to:boisson:detail','get:detail:taille',
        'get:taille:to:boisson:detail','get:detail:taille'])]
    private $qteBoissondanMenu;

    #[ORM\ManyToOne(targetEntity: MenuBoisson::class, inversedBy: 'menuTailles')]
    #[Groups(['post:view:menu','get:detail:taille'])]
    private $taille;     // $menuTaille

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuTailles')]
    #[Groups(['get:detail:taille'])]
    private $menu;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(?int $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getQteBoissondanMenu(): ?int
    {
        return $this->qteBoissondanMenu;
    }

    public function setQteBoissondanMenu(int $qteBoissondanMenu): self
    {
        $this->qteBoissondanMenu = $qteBoissondanMenu;

        return $this;
    }

    public function getTaille(): ?MenuBoisson
    {
        return $this->taille;
    }

    public function setTaille(?MenuBoisson $taille): self
    {
        $this->taille = $taille;

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
