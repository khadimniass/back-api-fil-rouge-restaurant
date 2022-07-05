<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Complement;
use App\Repository\BoissonRepository;
use App\Repository\FriteRepository;

class ComplementProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $frite;
    private $boisson;
    public function __construct(FriteRepository $frite, BoissonRepository $boisson)
    {
        $this->boisson=$boisson;
        $this->frite=$frite;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        return [
            'boisson'=>$this->boisson->findAll(),
            'frite'=>$this->frite->findAll()
        ];
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
       return $resourceClass==Complement::class;
    }
}