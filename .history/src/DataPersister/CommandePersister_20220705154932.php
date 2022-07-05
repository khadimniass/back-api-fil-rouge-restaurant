<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\{Client, Commande};
use App\service\Archiver;
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

        dd(($this->token->getUser()));
         $data->setClient($this->token->getUser());
        // dd('error intercepted');
        //  dd($data);
        $this->_entityManager->persist($data);
        dd($data);
        $this->_entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        Archiver::archiver($data);
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }
}