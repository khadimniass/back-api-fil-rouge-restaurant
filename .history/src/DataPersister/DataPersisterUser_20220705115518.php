<?php

namespace App\DataPersister;

use App\service\Archiver;
use App\Entity\{User, Livreur, Client};
use App\service\ServiceMailer;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 *
 */
class DataPersisterUser implements ContextAwareDataPersisterInterface
{
    private $_entityManager;
    private $_passwordEncoder;
    private $_serviceMailer;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordEncoder,
        ServiceMailer $serviceMailer,
        TokenStorageInterface $token
    ) {
        $this->_entityManager = $entityManager;
        $this->_passwordEncoder = $passwordEncoder;
        $this->_serviceMailer=$serviceMailer;
        $this->token = $token->getToken();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data, array $context = [])
    {
        dd()
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->_passwordEncoder->hashPassword(
                    $data,
                    $data->getPlainPassword()
                    )
                );
                $data->eraseCredentials();
            $data->tokenGenerator();
            $data->arrayRoles();
        }
        if ($data instanceof Livreur)
            $data->setGestionnaire($this->token->getUser());
//        $this->_serviceMailer->sendEmail($data);
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }
    /**
     * {@inheritdoc}
     */
    /**
     * @param User $data
     */
    public function remove($data, array $context = [])
    {
        Archiver::archiver($data);
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }
}