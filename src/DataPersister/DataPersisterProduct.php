<?php

namespace App\DataPersister;

use App\Entity\{Produit};
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

    public function persist($data, array $context = [])
    {
        $data->setGestionnaire($this->token->getUser());
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
    /**
     * {@inheritdoc}
     **/
    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}