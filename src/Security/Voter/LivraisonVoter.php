<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;
use \App\Entity\Livraison;

class LivraisonVoter extends Voter
{
    private $_security;
    public function __construct(Security $security)
    {
        $this->_security=$security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ["CAN_EDIT","CAN_READ","CAN_CREAT"])
            && $subject instanceof Livraison;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case "CAN_EDIT":
                $this->_security->isGranted("ROLE_GESTIONNAIRE");
                return true;
            case "CAN_READ":
                $this->_security->isGranted("ROLE_VISITEUR");
                return true;
            case "CAN_CREAT":
                $this->_security->isGranted("ROLE_GESTIONNAIRE");
                return true;
        }
        return false;
    }
}
