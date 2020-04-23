<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AnimalVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['update', 'delete'])
            && $subject instanceof \App\Entity\Animal;
    }

    protected function voteOnAttribute($attribute, $animal, TokenInterface $token)
    {
        $user = $token->getUser();
        $userId = $user->getId();
    
        
        if (!$user instanceof UserInterface) {
                return false;
            }   
            switch ($attribute) {
                case 'update':
                   
                    return $animal->getUser()->getId() == $userId;
                    
                break;
                case 'delete':
                    
                    return $animal->getUser()->getId() == $userId;
                    
                break;
            }
        return false;
    }
   
}
