<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreurRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;

#[ORM\Entity(repositoryClass: LivreurRepository::class)]
#[ApiResource(
    collectionOperations:[
    "get" =>[
        'method' => 'get',
        'status' => Response::HTTP_OK,
        'normalization_context' =>['groups' => ['user:read:simple']],
    ],

    "post"],

    itemOperations:["put","get"]
)]
class Livreur extends User
{
    // #[ORM\Id]
    // #[ORM\GeneratedValue]
    // #[ORM\Column(type: 'integer')]
    // private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $matriculeMoto;

    // public function getId(): ?int
    // {
    //     return $this->id;
    // }

    public function getMatriculeMoto(): ?string
    {
        return $this->matriculeMoto;
    }

    public function setMatriculeMoto(string $matriculeMoto): self
    {
        $this->matriculeMoto = $matriculeMoto;

        return $this;
    }
}
