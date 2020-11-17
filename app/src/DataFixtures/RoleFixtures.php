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
        $PermissionsAdmin->setSheetId("ROLE_ADMIN");

	$PermissionsContributeur = new Permissions();
        $PermissionsContributeur->setSheetId("ROLE_CONTRIBUTEUR");

	$PermissionsUtilisateur = new Permissions();
        $PermissionsUtilisateur->setSheetId("ROLE_UTILISATEUR");

	$entityRoleAdmin = new EntityRoles();
        $entityRoleAdmin->setName("ROLE_ADMIN");
        $entityRoleAdmin->setEditable(False);
	$entityRoleAdmin->setPermissions($sheetPermissionAdmin);

	$entityRoleContributeur = new EntityRoles();
        $entityRoleContributeur->setName("ROLE_CONTRIBUTEUR");
        $entityRoleContributeur->setEditable(False);
	$entityRoleContributeur->setPermissions($sheetPermissionContributeur);

	$entityRoleUtilisateur = new EntityRoles();
        $entityRoleUtilisateur->setName("ROLE_UTILISATEUR");
        $entityRoleUtilisateur->setEditable(False);
	$entityRoleUtilisateur->setPermissions($sheetPermissionUtilisateur);

        $manager->persist($PermissionsAdmin);
        $manager->persist($PermissionsContributeur);
        $manager->persist($PermissionsUtilisateur);
        $manager->persist($entityRoleAdmin);
        $manager->persist($entityRoleContributeur);
        $manager->persist($entityRoleUtilisateur);

        $manager->flush();
    }
}
