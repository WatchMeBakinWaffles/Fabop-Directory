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
	$sheetPermissionAdmin=$mongoman->insertSingle("permissions_user",[
		'shows'=>'RW',
		'tags'=>'RW',
		'peoples'=>'RW',
		'users'=>'RW',
		'models'=>'RW',
		'institutions'=>'RW',
		'roles'=>'RW',
		'import'=>True,
		'export'=>True,
		'connection'=>True,
		'restaurations'=>'RW'
	]);
	$sheetPermissionContributeur=$mongoman->insertSingle("permission_user",[
		'shows'=>'RW',
		'tags'=>'RW',
		'peoples'=>'RW',
		'users'=>'',
		'models'=>'',
		'institutions'=>'RW',
		'roles'=>'',
		'import'=>True,
		'export'=>True,
		'connection'=>True,
		'restaurations'=>''
	]);
	$sheetPermissionUtilisateur=$mongoman->insertSingle("permission_user",[
		'shows'=>'R',
		'tags'=>'R',
		'peoples'=>'R',
		'users'=>'',
		'models'=>'',
		'institutions'=>'',
		'roles'=>'',
		'import'=>False,
		'export'=>True,
		'connection'=>True,
		'restaurations'=>''
	]);

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
