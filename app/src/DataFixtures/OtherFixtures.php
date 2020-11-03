<?php

namespace App\DataFixtures;

use App\Entity\EntityPeople;
use App\Entity\EntityInstitutions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Utils\MongoManager;

class OtherFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $mongoman = new MongoManager();

        $sheetInstitutionId=$mongoman->insertSingle("Entity_institution_sheet",[]);
        $entityInstitution = new EntityInstitutions();
        $entityInstitution->setName("Institution test");
        $entityInstitution->setRole("TEST");
        $entityInstitution->setSheetId($sheetInstitutionId);

        $manager->persist($entityInstitution);

        $manager->flush();
    }
}
