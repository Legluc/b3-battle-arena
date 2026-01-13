<?php

namespace App\Security\Voter;

use App\Entity\Joueur;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class JoueurVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === 'EDIT' && $subject instanceof Joueur;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Si l'utilisateur n'est pas connecté, refus
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Un admin peut tout éditer
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        // Vérifie que $user est bien un Joueur
        if (!$user instanceof Joueur) {
            return false;
        }

        // Un joueur ne peut éditer que son propre profil
        return $subject->getId() === $user->getId();
    }
}
