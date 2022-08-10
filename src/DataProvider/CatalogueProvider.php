<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Catalogue;
use App\Repository\BurgerRepository;
use App\Repository\MenuRepository;

class CatalogueProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(MenuRepository $menuRepository, BurgerRepository $burgerRepository)
    {
        $this->menuRepository=$menuRepository;
        $this->burgerRepository=$burgerRepository;
    }
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        return [
            'menus'=>$this->menuRepository->findAll(),
            'burgers'=>$this->burgerRepository->findAll()
        ];
    }
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Catalogue::class;
    }
}