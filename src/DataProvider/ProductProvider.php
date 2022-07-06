<?php

namespace App\DataProvider;

use App\Entity\{Menu,Frite,Burger,Boisson};
use App\Repository\ProduitRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;

class ProductProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $repository;
    public function __construct(ProduitRepository $repository)
    {
        $this->repository=$repository;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $produits = $this->repository->findAll();
        foreach ($produits as $prod){
            if ($prod->getImage()) {
                //dd($prod);
                // base64_encode(stream_get_contents($prod->getImage()))
              //  $image=stream_get_contents(fopen($prod->getImage(), 'r'));
               // $prod->setImage($image);
            }
        }
        dd($produits);
        return $produits;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass==Menu::class || $resourceClass==Boisson::class || $resourceClass==Frite::class || $resourceClass==Burger::class;
    }
}