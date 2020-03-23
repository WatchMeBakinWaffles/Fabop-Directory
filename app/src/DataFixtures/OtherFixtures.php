<?php

namespace App\DataFixtures;

use App\Entity\EntityPeople;
use App\Entity\EntityInstitutions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Utils\MongoManager;

class OtherFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $mongoman = new MongoManager();

        $sheetInstitutionId=$mongoman->insertSingle("Entity_institution_sheet",[]);
        $entityInstitution = new EntityInstitutions();
        $entityInstitution->setName("Institution root");
        $entityInstitution->setRole("ROOT");
        $entityInstitution->setSheetId($sheetInstitutionId);

        $manager->persist($entityInstitution);

        $manager->flush();
    }
}
