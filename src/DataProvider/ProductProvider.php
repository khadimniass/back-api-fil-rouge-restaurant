<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Boisson;
use App\Entity\Frite;
use App\Entity\Menu;
use App\Entity\Produit;
use App\Repository\ProduitRepository;

class ProductProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $repository;
    public function __construct(ProduitRepository $repository)
    {
        $this->repository=$repository;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $produits = $this->repository->findBy([],['etat'=>1]);
        foreach ($produits as $prod){
            if ($prod->getImage()) {
               // dd($prod->getImageBinary());

                $prod->getImage();
                //dd($prod);
                // base64_encode(stream_get_contents($prod->getImage()))
            }
        }
        return $produits;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass==Menu::class || $resourceClass==Boisson::class || $resourceClass==Frite::class;
    }
}