<?php

namespace App\DataFixtures;

use App\Entity\EntityRoles;
use App\Entity\Permissions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Utils\MongoManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $mongoman = new MongoManager();
	$sheetPermissionAdmin=$mongoman->insertSingle("PermissionAdmin",[]);
	$sheetPermissionContributeur=$mongoman->insertSingle("PermissionContributeur",[]);
	$sheetPermissionUtilisateur=$mongoman->insertSingle("PermissionUtilisateur",[]);

	$PermissionsAdmin = new Permissions();
        $PermissionsAdmin->setSheetId($sheetPermissionAdmin);

	$PermissionsContributeur = new Permissions();
        $PermissionsContributeur->setSheetId($sheetPermissionContributeur);

	$PermissionsUtilisateur = new Permissions();
        $PermissionsUtilisateur->setSheetId($sheetPermissionUtilisateur);

	$entityRoleAdmin = new EntityRoles();
        $entityRoleAdmin->setNom("ROLE_ADMIN");
        $entityRoleAdmin->setEditable(False);
	$entityRoleAdmin->setPermissions($PermissionsAdmin);

	$entityRoleContributeur = new EntityRoles();
        $entityRoleContributeur->setNom("ROLE_CONTRIBUTEUR");
        $entityRoleContributeur->setEditable(False);
	$entityRoleContributeur->setPermissions($PermissionsContributeur);

	$entityRoleUtilisateur = new EntityRoles();
        $entityRoleUtilisateur->setNom("ROLE_UTILISATEUR");
        $entityRoleUtilisateur->setEditable(False);
	$entityRoleUtilisateur->setPermissions($PermissionsUtilisateur);

        $manager->persist($PermissionsAdmin);
        $manager->persist($PermissionsContributeur);
        $manager->persist($PermissionsUtilisateur);
        $manager->persist($entityRoleAdmin);
        $manager->persist($entityRoleContributeur);
        $manager->persist($entityRoleUtilisateur);

        $manager->flush();
    }
}
