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
        return in_array($attribute, ['IMPORT', 'EXPORT', 'CONNECTION', 'SUB_MENU', 'PEOPLES', 'INSTITUTIONS', 'SHOWS', 'TAGS', 'ADMIN','USERS', 'MODELS','RESTAURATIONS']);
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
		    case 'IMPORT':
		        if ($data_permissions["import"] == True){
		         	return true;
			}
		    case 'EXPORT':
		        if ($data_permissions["export"] == True){
		         	return true;
			}
		    case 'CONNECTION':
		        if ($data_permissions["connection"] == True){
		         	return true;
			}
		    case 'SUB_MENU':
		        if ($data_permissions["peoples"] == "R"  || $data_permissions["peoples"] == "W" || $data_permissions["peoples"] == "RW"){
		         	return true;
			}
		        if ($data_permissions["institutions"] == "R"  || $data_permissions["institutions"] == "W" || $data_permissions["institutions"] == "RW"){
		         	return true;
			}
		        if ($data_permissions["shows"] == "R"  || $data_permissions["shows"] == "W" || $data_permissions["shows"] == "RW"){
		         	return true;
			}
		        if ($data_permissions["tags"] == "R"  || $data_permissions["tags"] == "W" || $data_permissions["tags"] == "RW"){
		         	return true;
			}
		    case 'PEOPLES':
		        if ($data_permissions["peoples"] == "R"  || $data_permissions["peoples"] == "W" || $data_permissions["peoples"] == "RW"){
		         	return true;
			}
		    case 'INSTITUTIONS':
		        if ($data_permissions["institutions"] == "R"  || $data_permissions["institutions"] == "W" || $data_permissions["institutions"] == "RW"){
		         	return true;
			}
		    case 'SHOWS':
		        if ($data_permissions["shows"] == "R"  || $data_permissions["shows"] == "W" || $data_permissions["shows"] == "RW"){
		         	return true;
			}
		    case 'TAGS':
		        if ($data_permissions["tags"] == "R"  || $data_permissions["tags"] == "W" || $data_permissions["tags"] == "RW"){
		         	return true;
			}
		    case 'ADMIN':
		        if ($data_permissions["users"] == "R"  || $data_permissions["users"] == "W" || $data_permissions["users"] == "RW"){
		         	return true;
			}
			if ($data_permissions["roles"] == "R"  || $data_permissions["roles"] == "W" || $data_permissions["roles"] == "RW"){
		         	return true;
			}
		        if ($data_permissions["models"] == "R"  || $data_permissions["models"] == "W" || $data_permissions["models"] == "RW"){
		         	return true;
			}
		        if ($data_permissions["restaurations"] == "R"  || $data_permissions["restaurations"] == "W" || $data_permissions["restaurations"] == "RW"){
		         	return true;
			}
		    case 'USERS':
		        if ($data_permissions["users"] == "R"  || $data_permissions["users"] == "W" || $data_permissions["users"] == "RW"){
		         	return true;
			}
			if ($data_permissions["roles"] == "R"  || $data_permissions["roles"] == "W" || $data_permissions["roles"] == "RW"){
		         	return true;
			}
		    case 'MODELS':
		        if ($data_permissions["models"] == "R"  || $data_permissions["models"] == "W" || $data_permissions["models"] == "RW"){
		         	return true;
			}
		    case 'RESTAURATIONS':
		        if ($data_permissions["restaurations"] == "R"  || $data_permissions["restaurations"] == "W" || $data_permissions["restaurations"] == "RW"){
		         	return true;
			}
		}
	}
        return false;
    }
}
