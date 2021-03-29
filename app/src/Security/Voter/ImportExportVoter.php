<?php

namespace App\Security\Voter;

use App\Entity\EntityRoles;
use App\Entity\Permissions;
use App\Utils\MongoManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ImportExportVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['import', 'export', 'connection', 'sub_menu', 'peoples', 'institutions', 'show', 'tags', 'admin','users', 'models','restaurations']);
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
            switch ($attribute) {
                case 'import':
                case 'export':
                case 'connection':
                case 'admin':
                    return $data_permissions[$attribute];
                    break;
                case 'user':
                    return !$data_permissions[$attribute];
                    break;
                case 'sub_menu':
                    //if (!$data_permissions["peoples"] == "" || !$data_permissions["institutions"] == "" || !$data_permissions["shows"] == "" || !$data_permissions["tags"] == "")
                    return true;
                    break;
                default :
                    //if(!$data_permissions[$attribute] == "")
                    return true;
                    break;
            }
        }
        return false;
    }
}
