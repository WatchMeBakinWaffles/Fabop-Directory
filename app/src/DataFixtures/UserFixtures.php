<?php

namespace App\DataFixtures;

use App\Entity\EntityUser;
use App\Entity\EntityInstitutions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
        $user_root = new EntityUser();
        $user_root->setEmail("root@root.fr");
        $user_root->setRoles(array("ROLE_ADMIN"));
        $user_root->setFirstname("root");
        $user_root->setLastname("root");
        $user_root->setPassword('$2y$12$eGhtebnhdb0BDs72VBLYneNt6Fz9QYM5FLL6P86irhhwahamUDhEm');
        $user_root->setApiToken("API_ROOT");

        $manager->persist($user_root);

        $manager->flush();

    }
}
