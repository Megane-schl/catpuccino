<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserVoter extends Voter
{
    public const EDIT = 'USER_EDIT';
    public const VIEW = 'USER_VIEW';
    public const ROLE = 'USER_ROLE';
    public const BAN = 'USER_BAN';
    public const DELETE = 'USER_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::ROLE, self::BAN, self::DELETE])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            $vote?->addReason('The user must be logged in to access this resource.');

            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        $isAdmin = in_array('ROLE_ADMIN', $user->getRoles());
        $isSelf = $subject === $user;

        switch ($attribute) {
            case self::EDIT:
            case self::VIEW:
                //because an admin can view and edit himself aswell and an user can edit himself
                return $isAdmin || $isSelf;
                break;
            case self::ROLE:
            case self::BAN:
            case self::DELETE:
                // an admin can't change his own role or ban / delete himself
                return $isAdmin && !$isSelf;
        }

        return false;
    }
}
