<?php

namespace App\Security\Voter;

use App\Entity\EntityRoles;
use App\Entity\Permissions;
use App\Utils\MongoManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class EntityTagsVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['POST_EDIT', 'POST_VIEW'])
            && $subject instanceof \App\Entity\EntityTags;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $roles = $user->getEntityRoles();
        foreach ($roles as $role){
            $permissions = $role->getPermissions();
            $mongoman = new MongoManager();
            $data_permissions = $mongoman->getDocById("permissions_user",$permissions->getSheetId());
            // ... (check conditions and return true to grant permission) ...
            switch ($attribute) {
                case 'POST_EDIT':
                    // logic to determine if the user can EDIT
                    // return true or false
                    return PermissionCalculator::check($data_permissions, "tags","write");
                    break;
                case 'POST_VIEW':
                    // logic to determine if the user can VIEW
                    // return true or false
                    return PermissionCalculator::check($data_permissions,"tags","read");
                    break;
                default:
                    return false;
            }
        }
    }
}
