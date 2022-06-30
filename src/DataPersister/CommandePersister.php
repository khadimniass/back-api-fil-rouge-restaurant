<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Client;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CommandePersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;
    private ?TokenInterface $token;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $token)
    {
        $this->_entityManager = $entityManager;
        $this->token = $token->getToken();
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Commande;
    }

    public function persist($data, array $context = [])
    {
        dd($data->getGestionnaire());
        dd(($this->token->getUser()));
        if ($data instanceof Client)
            $this->setClient($this->token->getUser());
        dd('error intercepted');
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}