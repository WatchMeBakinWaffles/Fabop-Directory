<?php

namespace App\DataFixtures;

use App\Entity\EntityPeople;
use App\Entity\EntityInstitutions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class OtherFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $entity = new EntityInstitutions();
        $entity->setName("Institution 1");
        $entity->setRole("Role");

        $manager->persist($entity);

        $manager->flush();
    }
}
