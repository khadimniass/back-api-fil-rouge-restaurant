<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Quartier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class QuartierPersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;
    private ?TokenInterface $_token;

    public function __construct(EntityManagerInterface $entityManager,TokenStorageInterface $token)
    {
        $this->_token = $token->getToken();
        $this->_entityManager=$entityManager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Quartier;
    }

    public function persist($data, array $context = [])
    {
        $data->setGestionnaire($this->_token->getUser());
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }
}