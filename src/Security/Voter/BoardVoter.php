<?php

namespace App\Security\Voter;

use App\Entity\Board;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class BoardVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['BOARD_EDIT', 'BOARD_VIEW', 'BOARD_DELETE'])
            && $subject instanceof Board;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // you know $subject is a Board object, thanks to `supports()`
        /** @var Board $board */
        $board = $subject;

        switch ($attribute) {
            case 'BOARD_DELETE':
                return $this->canDelete($board, $user);

                break;
            case 'BOARD_EDIT':
                return $this->canEdit($board, $user);

                break;
            case 'BOARD_VIEW':
                return true;

                break;
        }

        return false;
    }

    private function canDelete(Board $board, User $user)
    {
        // Only owners or admins can delete boards
        return $board->getOwner() === $user
            || $this->security->isGranted('ROLE_ADMIN');
    }

    private function canEdit(Board $board, User $user)
    {
        // Either admins or moderators can adminster a board
        if ($this->security->isGranted('ROLE_ADMIN')
            || $this->security->isGranted('ROLE_MODERATOR')) {
            return true;
        }

        // Or if the user owns the board
        return $board->getOwner() === $user;
    }
}
