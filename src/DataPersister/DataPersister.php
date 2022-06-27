<?php

namespace App\DataPersister;

use     App\Entity\User;
use App\service\ServiceMailer;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 *
 */
class DataPersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;
    private $_passwordEncoder;
    private $_serviceMailer;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordEncoder,
        ServiceMailer $serviceMailer

    ) {
        $this->_entityManager = $entityManager;
        $this->_passwordEncoder = $passwordEncoder;
        $this->_serviceMailer=$serviceMailer;
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
        $this->_entityManager->persist($data);
        $this->_serviceMailer->sendEmail($data);
        $this->_entityManager->flush();

    }
    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}