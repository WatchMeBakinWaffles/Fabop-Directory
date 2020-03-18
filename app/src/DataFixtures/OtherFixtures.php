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
        

        // $manager->persist($entity);

        // $manager->flush();
    }
}
