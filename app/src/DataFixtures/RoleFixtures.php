<?php

namespace App\DataFixtures;

use App\Entity\EntityUser;
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
    $json = '{
        "label": "Admin",
        "permissions": [
            {
                "entityType": "show",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "tags",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "peoples",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "users",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "models",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "institutions",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "roles",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "restaurations",
                "rights": [
                        {
                            "filters": [
                                {
                                    "field": "*",
                                    "value": "*"
                                }
                            ],
                            "read": 1,
                            "write": 1
                        }
                ]
            }
        ],
        "import":true,
        "export": true,
        "connection": true,
        "admin": true
    }';
    $array = json_decode($json);
	$sheetPermissionAdmin=$mongoman->insertSingle("permissions_user",$array);
	$json = '{
	    "label": "Contributeur",
        "permissions": [
            {
                "entityType": "show",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "tags",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "peoples",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "users",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": -1,
                        "write": -1
                    }
                ]
            },
            {
                "entityType": "models",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": -1,
                        "write": -1
                    }
                ]
            },
            {
                "entityType": "institutions",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "roles",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": -1,
                        "write": -1
                    }
                ]
            },
            {
                "entityType": "restaurations",
                "rights": [
                        {
                            "filters": [
                                {
                                    "field": "*",
                                    "value": "*"
                                }
                            ],
                            "read": -1,
                            "write": -1
                        }
                ]
            }
        ],
        "import":true,
        "export": true,
        "connection": true,
        "admin": false
    }';
    $array = json_decode($json);
	$sheetPermissionContributeur=$mongoman->insertSingle("permissions_user",$array);
	$json = '{
	    "label": "User",
        "permissions": [
            {
                "entityType": "show",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "tags",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "peoples",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "users",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": -1,
                        "write": -1
                    }
                ]
            },
            {
                "entityType": "models",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": -1,
                        "write": -1
                    }
                ]
            },
            {
                "entityType": "institutions",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": 1,
                        "write": 1
                    }
                ]
            },
            {
                "entityType": "roles",
                "rights": [
                    {
                        "filters": [
                            {
                                "field": "*",
                                "value": "*"
                            }
                        ],
                        "read": -1,
                        "write": -1
                    }
                ]
            },
            {
                "entityType": "restaurations",
                "rights": [
                        {
                            "filters": [
                                {
                                    "field": "*",
                                    "value": "*"
                                }
                            ],
                            "read": -1,
                            "write": -1
                        }
                ]
            }
        ],
        "import":false,
        "export": true,
        "connection": true,
        "admin": false
    }';
	$array = json_decode($json);
	$sheetPermissionUtilisateur=$mongoman->insertSingle("permissions_user",$array);

	$PermissionsAdmin = new Permissions();
        $PermissionsAdmin->setSheetId($sheetPermissionAdmin);

	$PermissionsContributeur = new Permissions();
        $PermissionsContributeur->setSheetId($sheetPermissionContributeur);

	$PermissionsUtilisateur = new Permissions();
        $PermissionsUtilisateur->setSheetId($sheetPermissionUtilisateur);

	$manager->persist($PermissionsAdmin);
        $manager->persist($PermissionsContributeur);
        $manager->persist($PermissionsUtilisateur);
	$manager->flush();

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

        $manager->persist($entityRoleAdmin);
        $manager->persist($entityRoleContributeur);
        $manager->persist($entityRoleUtilisateur);
	$manager->flush();

	$user_root = new EntityUser();
        $user_root->setEmail("root@root.fr");
        $user_root->setRoles(array("ROLE_ADMIN"));
	$user_root->addEntityRole($entityRoleAdmin);
        $user_root->setFirstname("root");
        $user_root->setLastname("root");
        $user_root->setPassword('$2y$12$eGhtebnhdb0BDs72VBLYneNt6Fz9QYM5FLL6P86irhhwahamUDhEm');
        $user_root->setApiToken("API_ROOT");

	$user_test = new EntityUser();
        $user_test->setEmail("test@test.fr");
        $user_test->setRoles(array());
	$user_test->addEntityRole($entityRoleUtilisateur);
        $user_test->setFirstname("test");
        $user_test->setLastname("test");
        $user_test->setPassword('$2y$12$eGhtebnhdb0BDs72VBLYneNt6Fz9QYM5FLL6P86irhhwahamUDhEm');
        $user_test->setApiToken("API_TEST");

        $manager->persist($user_root);
        $manager->persist($user_test);

        $manager->flush();
    }
}
