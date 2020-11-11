<?php

namespace App\Utils;

use App\Entity\EntityShows;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\EntityPeople;
use App\Entity\EntityInstitutions;
use App\Utils\MongoManager;

class XLSXReader
{
    private $em;
    private $user;

    public function __construct(EntityManagerInterface $em, $user)
    {
        $this->em = $em;
        $this->user = $user;
    }

    public function readAll(Request $request, string $filename)
    {

        $mongoman = new MongoManager();

        $reader = ReaderEntityFactory::createReaderFromFile($filename);

        $reader->open($filename);

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                if ($rowNumber == 1) {
                    foreach ($row->getCells() as $cell) {
                        $title[] = $cell->getValue();
                    }
                }

                if ($rowNumber > 1 and $title[1] == 'Prénom') {
                    $cells = $row->getCells();
                    $entityPerson = new EntityPeople();
                    $entityInstitutions = new EntityInstitutions();

                    // Mise en bdd MySQL des données venu d'excel
                    $i = 0;
                    //Name
                    $entityPerson->setName($cells[$i]->getValue());
                    $i++;

                    // FirstName
                    $entityPerson->setFirstname($cells[$i]->getValue());
                    $i++;
                    // BirthDate
                    if (is_string($cells[$i]->getValue())) {
                        $date = new \DateTime($cells[$i]->getValue());
                    } else {
                        $date = $cells[$i]->getValue();
                    }
                    $entityPerson->setBirthdate($date);
                    $i++;
                    // postal_code
                    $entityPerson->setPostalCode($cells[$i]->getValue());
                    $i++;
                    // city
                    $entityPerson->setCity($cells[$i]->getValue());
                    $i++;
                    // newsletter
                    $entityPerson->setNewsletter($cells[$i]->getValue());
                    $i++;
                    // adresse_mailing
                    $entityPerson->setAdresseMailing($cells[$i]->getValue());
                    $i++;
                    // add_date
                    $entityPerson->setAddDate(new \DateTime("now"));

                    if (in_array('ROLE_ADMIN', $this->user->getRoles())) {
                        // institution_id
                        $institut = $this->em->getRepository(EntityInstitutions::class)
                            ->findOneByName($cells[$i]->getValue());


                        // Si l'institut n'existe pas (null), on la crée
                        if ($institut == null) {
                            $entityInstitutions->setName($cells[$i]->getValue());
                            $entityInstitutions->setRole("Ceci est une institution");

                            if (null != $request->request->get('institution_data')) {
                                $sheetID = $mongoman->insertSingle("Entity_institution_sheet", $request->request->get('institution_data'));
                            } else {
                                $sheetID = $mongoman->insertSingle("Entity_institution_sheet", []);
                            }
                            $entityInstitutions->setSheetId($sheetID);
                            $this->em->persist($entityInstitutions);
                            $institut = $entityInstitutions;
                        }
                        $entityPerson->setInstitution($institut);
                    } else {
                        $institut = $this->user->getInstitution();
                        $entityPerson->setInstitution($institut);
                    }
                    $i++;
                    if ((sizeof($cells)) > $i) {
                        for ($i; sizeof($cells) > $i; $i++) {
                            $data[$title[$i]] = $cells[$i]->getValue();
                        }
                        if (isset($data)) {
                            $sheetId = $mongoman->insertSingle("Entity_person_sheet", $data);
                            unset($data);
                        } else {
                            $sheetId = $mongoman->insertSingle("Entity_person_sheet", []);
                        }
                    } else {
                        $sheetId = $mongoman->insertSingle("Entity_person_sheet", []);
                    }
                    //sheet_id
                    $entityPerson->setSheetId($sheetId);

                    $this->em->persist($entityPerson);
                    $this->em->flush();
                }

                if ($rowNumber > 1 and $title[1] == 'Rôle') {

                    $cells = $row->getCells();
                    $entityInstitutions = new EntityInstitutions();

                    // Mise en bdd MySQL des données venu d'excel
                    $i = 0;

                    //Name
                    $entityInstitutions->setName($cells[$i]->getValue());
                    $i++;

                    //Rôle
                    $entityInstitutions->setRole($cells[$i]->getValue());
                    $i++;

                    if ((sizeof($cells)) > $i) {
                        for ($i; sizeof($cells) > $i; $i++) {
                            $data[$title[$i]] = $cells[$i]->getValue();
                        }
                        if (isset($data)) {
                            $sheetId = $mongoman->insertSingle("Entity_institutions_sheet", $data);
                            unset($data);
                        } else {
                            $sheetId = $mongoman->insertSingle("Entity_institutions_sheet", []);
                        }
                    } else {
                        $sheetId = $mongoman->insertSingle("Entity_institutions_sheet", []);
                    }
                    //sheet_id
                    $entityInstitutions->setSheetId($sheetId);

                    $this->em->persist($entityInstitutions);
                    $this->em->flush();
                }

                if ($rowNumber > 1 and $title[1] == 'Année') {

                    $cells = $row->getCells();
                    $entityShows = new EntityShows();

                    // Mise en bdd MySQL des données venu d'excel
                    $i = 0;

                    //Name
                    $entityShows->setName($cells[$i]->getValue());
                    $i++;

                    //Rôle
                    $entityShows->setYear($cells[$i]->getValue());
                    $i++;

                    if ((sizeof($cells)) > $i) {
                        for ($i; sizeof($cells) > $i; $i++) {
                            $data[$title[$i]] = $cells[$i]->getValue();
                        }
                        if (isset($data)) {
                            $sheetId = $mongoman->insertSingle("Entity_shows_sheet", $data);
                            unset($data);
                        } else {
                            $sheetId = $mongoman->insertSingle("Entity_shows_sheet", []);
                        }
                    } else {
                        $sheetId = $mongoman->insertSingle("Entity_shows_sheet", []);
                    }
                    //sheet_id
                    $entityShows->setSheetId($sheetId);

                    $this->em->persist($entityShows);
                    $this->em->flush();

                }

            }

            $reader->close();

        }

    }

}