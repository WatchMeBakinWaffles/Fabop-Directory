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

    public function readFirstLine($filePath){

        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($filePath);
        $firsLine = "";

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                if($rowNumber == 1){
                    foreach ($row->getCells() as $cell) {
                        $title[] = $cell->getValue();
                        $firsLine = $title;
                    }
                }
            }
        }
        return $firsLine[0];
    }

    public function readCustomSheet(Request $request, string $filename){

        $mongoman = new MongoManager();
        $reader = ReaderEntityFactory::createReaderFromFile($filename);

        $reader->open($filename);

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                if ($rowNumber == 1) {
                    foreach ($row->getCells() as $cell) {
                        $header[] = $cell->getValue();
                    }
                    // On récupère le header
                    $doc = $mongoman->getDocById("Entity_Custom_sheet",$header[1]);
                    $fields = [];
                    foreach ($doc as $item => $value ){
                        $fields[$item] = $value;
                    }
                } if ($rowNumber > 2) {
                    $cells = $row->getCells();
                    $entityInstitution = new EntityInstitutions();
                    $entityPeople = new EntityPeople();
                    // Mise en bdd MySQL des données venu d'excel
                    if ($fields['Nom']) {
                        $entityPeople->setName($row->getCells()[0]->getValue());

                    }
                    if ($fields['Prénom']) {
                        $entityPeople->setFirstname($row->getCells()[1]->getValue());
                    }
                    if ($fields['Date de naissance']) {
                        if (is_string($row->getCells()[2]->getValue())) {
                            $date = new \DateTime($row->getCells()[2]->getValue());
                        } else {
                            $date = $row->getCells()[2]->getValue();
                        }
                        $entityPeople->setBirthdate($date);
                    }
                    if ($fields['Code postal']) {
                        $entityPeople->setPostalCode($row->getCells()[3]->getValue());
                    }
                    if ($fields['Ville']) {
                        $entityPeople->setCity($row->getCells()[4]->getValue());
                    }
                    if ($fields['Abonné à la newsletter']) {
                        $entityPeople->setNewsletter($row->getCells()[5]->getValue());
                    }
                    if ($fields['Adresse mail']) {
                        $entityPeople->setAdresseMailing($row->getCells()[6]->getValue());
                    }
                    if ($fields['Institution']) {
                        if (in_array('ROLE_ADMIN', $this->user->getRoles())) {
                            // institution_id
                            $institut = $this->em->getRepository(EntityInstitutions::class)
                                ->findOneByName($row->getCells()[7]->getValue());

                            // Si l'institut n'existe pas (null), on la crée
                            if ($institut == null) {
                                $entityInstitution->setName($row->getCells()[7]->getValue());
                                $entityInstitution->setRole("CONTRIBUTEUR");

                                if (null != $request->request->get('institution_data')) {
                                    $sheetID = $mongoman->insertSingle("Entity_institution_sheet", $request->request->get('institution_data'));
                                } else {
                                    $sheetID = $mongoman->insertSingle("Entity_institution_sheet", []);
                                }
                                $entityInstitution->setSheetId($sheetID);
                                $this->em->persist($entityInstitution);
                                $institut = $entityInstitution;
                            }
                            $entityPeople->setInstitution($institut);
                        } else {
                            $institut = $this->user->getInstitution();
                        }
                    }
                    //Les champs supérieurs à 8 dans la lignes 2 vont directement dans mongodb
                    if ((sizeof($cells)) > 8) {
                        for ($i = 8; $i < (sizeof($cells)); $i++) {
                            $data[$fields['Complémentaires '.$i]] = $row->getCells()[$i]->getValue();
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
                    $entityPeople->setSheetId($sheetId);
                    $entityPeople->setAddDate(new \DateTime("now"));
                    $this->em->persist($entityPeople);
                    $this->em->flush();
                }
            }
            $reader->close();
        }
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
                    }
                    $i++;
                    if ((sizeof($cells)) > $i) {
                        for ($i; $i < sizeof($cells) ; $i++) {
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