<?php

namespace App\DataPersister;

use App\service\Archiver;
use App\service\CalculPrixMenu;
use App\Entity\{Menu, Produit};
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DataPersisterProduct implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    private ?TokenInterface $token;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $token)
    {
        $this->entityManager = $entityManager;
        $this->token = $token->getToken();
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
        if ($data->getImageBinary()){
            $data->setImage(file_get_contents($data->getImageBinary()));
          //  dd($data->getImage());
        }
        if ($data instanceof Menu){
            $data->setPrix(CalculPrixMenu::prixMenu());
        }
        //dd($data);
        $data->setGestionnaire($this->token->getUser());
        $data->setQuantity($this-  +1);
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
    /**
     * {@inheritdoc}
     **/
    /**
     * @param Produit $data
     */
    public function remove($data, array $context = [])
    {
        Archiver::archiver($data);
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
}