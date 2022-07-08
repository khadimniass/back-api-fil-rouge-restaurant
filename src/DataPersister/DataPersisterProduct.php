<?php

namespace App\DataPersister;

use App\service\Archiver;
use App\Entity\{Menu, Produit};
use App\service\CalculPrixMenu;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DataPersisterProduct implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    private ?TokenInterface $token;
    private $produitRepository;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $token, ProduitRepository $produitRepository)
    {
        $this->entityManager = $entityManager;
        $this->token = $token->getToken();
        $this->produitRepository = $produitRepository;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Produit;
    }
    /**
     * @param Produit $data
     */

    public function persist($data, array $context = [])
    {
        //dd($data);
        if ($data->getImageBinary()){
            $data->setImage(file_get_contents($data->getImageBinary()));
        }
        if ($data instanceof Menu){
            $data->setPrix(CalculPrixMenu::prixMenu($data,0.5));
            foreach ($data->getMenuBoissons() as $menu){
                dd($menu);
            }
        }
        dd($data);
        $data->setGestionnaire($this->token->getUser());
        $data->setQuantity(1);
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
    /**
     * {@inheritdoc}
     * @param Produit $data
     */
    public function remove($data, array $context = [])
    {
        Archiver::archiver($data);
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
}