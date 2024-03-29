<?php

namespace App\Security\Voter;

use App\Entity\Quartier;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class QuartierVoter extends Voter
{
    private $_security;
    public function __construct(Security $security)
    {
        $this->_security=$security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ["EDIT","CREAT"])
            && $subject instanceof Quartier;
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
            case "EDIT":
                $this->_security->isGranted("ROLE_GESTIONNAIRE");
                // logic to determine if the user can EDIT
                return true;
                break;
            case "CREAT":
                $this->_security->isGranted("ROLE_GESTIONNAIRE");
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
