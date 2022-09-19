<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ApiResource(
    collectionOperations:[
    "get" =>[
        'method' => 'get',
        'status' => Response::HTTP_OK,
        'normalization_context' =>['groups' => ['user:read:simple']]
    ],

    "post"
    ],
    itemOperations:["put","get","delete"]
)]
#[ApiFilter(SearchFilter::class, properties: ['login' => 'exact'])]
class Client extends User
{
    #[ORM\Column(type: 'string', length: 100)]
    private $adresse;


    public function __construct()
    {
        parent::__construct();
        $this->etat='actif';
    }
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }
}
