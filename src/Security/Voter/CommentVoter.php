<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['update', 'delete'])
            && $subject instanceof \App\Entity\Comment;
    }

    protected function voteOnAttribute($attribute, $comment, TokenInterface $token)
    {
        $user = $token->getUser();
        $userId = $user->getId();

        if (!$user instanceof UserInterface) {
            return false;
        }
        
        switch ($attribute) {
            case 'update':

                return $comment->getUser()->getId() == $userId;
                
                break;
            case 'delete':

                return $comment->getUser()->getId() == $userId;

                break;
        }

        return false;
    }
}
