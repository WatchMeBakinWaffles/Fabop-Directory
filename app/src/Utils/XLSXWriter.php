<?php

namespace App\Utils;

use App\Repository\EntityInstitutionsRepository;
use App\Repository\EntityPeopleRepository;
use App\Repository\EntityShowsRepository;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

class XLSXWriter
{

    public function writeAll(EntityPeopleRepository $entityPeopleRepository, $institution_id = null)
    {
        // Création Writer XLSX
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile("export.xlsx");

        // Récupération participants
        $people = array();


        if(isset($institution_id))
            $people = $entityPeopleRepository->findBy(['institution' => $institution_id]);

        else
            $people = $entityPeopleRepository->findAll();

        //Création des champs dynamique
        $mongoman = new MongoManager();
        $dynArray = array();
        foreach($people as $person){
            $array = $mongoman->getDocById("Entity_person_sheet",$person->getSheetId());
            array_splice($array,0,1);
            foreach($array as $key=>$value){
                if (!in_array($key,$dynArray))
                    $dynArray[] = $key;
            }
        }

        // Création première ligne avec noms de colonnes
        $firstLineCells = ["Nom", "Prénom", "Date de naissance", "Code postal", "Ville", "Abonné à la newsletter", "Adresse mail", "Institution"];
        $firstLineCells = array_merge($firstLineCells,$dynArray);
        $firstRow = WriterEntityFactory::createRowFromArray($firstLineCells);
        $writer->addRow($firstRow);

        // Ajout de toutes les personnes dans l'excel
        foreach ($people as $person) {
            $rowcells = [$person->getName(),
                         $person->getFirstname(),
                         $person->getBirthdate()->format("j-m-Y"),
                         $person->getPostalcode(),
                         $person->getCity(),
                         $person->getNewsletter(),
                         $person->getAdresseMailing(),
                         $person->getInstitution()->getName()];

            // AJOUT DES VALEURS MONGODB
            $array = $mongoman->getDocById("Entity_person_sheet",$person->getSheetId());
            array_splice($array,0,1);
            foreach($array as $key=>$value){
                for($i=8;sizeof($firstLineCells)>$i;$i++){
                    if (!isset($rowcells[$i])){
                        $rowcells[$i] = ''; // Mise à vide dans le cas où il n'y a pas de valeur
                    }
                    if ($firstLineCells[$i]==$key)
                        $rowcells[$i] = $value;
                }
            }
            $row = WriterEntityFactory::createRowFromArray($rowcells);
            $writer->addRow($row);
        }

        $writer->close();

    }

    public function write(Array $liste_id, EntityPeopleRepository $epr)
    {
        // Création Writer XLSX
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile("export_selectif.xlsx");

        //Création des champs dynamique
        $mongoman = new MongoManager();
        $dynArray = array();
        foreach ($liste_id as $id) {
            $person = $epr->find($id);
            $array = $mongoman->getDocById("Entity_person_sheet",$person->getSheetId());
            array_splice($array,0,1);
            foreach($array as $key=>$value){
                if (!in_array($key,$dynArray))
                    $dynArray[] = $key;
            }
        }

        // Création première ligne avec noms de colonnes
        $firstLineCells = ["Nom", "Prénom", "Date de naissance", "Code postal", "Ville", "Abonné à la newsletter", "Adresse mail", "Institution"];
        $firstLineCells = array_merge($firstLineCells,$dynArray);
        $firstRow = WriterEntityFactory::createRowFromArray($firstLineCells);
        $writer->addRow($firstRow);

        foreach ($liste_id as $id) {
            $person = $epr->find($id);
            $rowcells = [$person->getName(),
                         $person->getFirstname(),
                         $person->getBirthdate()->format("j-m-Y"),
                         $person->getPostalcode(),
                         $person->getCity(),
                         $person->getNewsletter(),
                         $person->getAdresseMailing(),
                         $person->getInstitution()->getName()];

            // AJOUT DES VALEURS MONGODB
            $array = $mongoman->getDocById("Entity_person_sheet",$person->getSheetId());
            array_splice($array,0,1);
            foreach($array as $key=>$value){
                for($i=8;sizeof($firstLineCells)>$i;$i++){
                    if (!isset($rowcells[$i])){
                        $rowcells[$i] = ''; // Mise à vide dans le cas où il n'y a pas de valeur
                    }
                    if ($firstLineCells[$i]==$key)
                        $rowcells[$i] = $value;
                }
            }
            $row = WriterEntityFactory::createRowFromArray($rowcells);
            $writer->addRow($row);
        }

        $writer->close();
    }

    public function writeInstitution(Array $liste_id, EntityInstitutionsRepository $eir)
    {
        // Création Writer XLSX
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile("export_selectif.xlsx");

        //Création des champs dynamique
        $mongoman = new MongoManager();
        $dynArray = array();
        foreach ($liste_id as $id) {
            $person = $eir->find($id);
            $array = $mongoman->getDocById("Entity_institution_sheet",$person->getSheetId());
            array_splice($array,0,1);
            foreach($array as $key=>$value){
                if (!in_array($key,$dynArray))
                    $dynArray[] = $key;
            }
        }

        // Création première ligne avec noms de colonnes
        $firstLineCells = ["nom", "role"];
        $firstLineCells = array_merge($firstLineCells,$dynArray);
        $firstRow = WriterEntityFactory::createRowFromArray($firstLineCells);
        $writer->addRow($firstRow);

        foreach ($liste_id as $id) {
            $institution = $eir->find($id);
            $rowcells = [$institution->getName(),
                         $institution->getRole()];

            // AJOUT DES VALEURS MONGODB
            $array = $mongoman->getDocById("Entity_institution_sheet",$institution->getSheetId());
            array_splice($array,0,1);
            foreach($array as $key=>$value){
                for($i=2;sizeof($firstLineCells)>$i;$i++){
                    if (!isset($rowcells[$i])){
                        $rowcells[$i] = ''; // Mise à vide dans le cas où il n'y a pas de valeur
                    }
                    if ($firstLineCells[$i]==$key)
                        $rowcells[$i] = $value;
                }
            }
            $row = WriterEntityFactory::createRowFromArray($rowcells);
            $writer->addRow($row);
        }

        $writer->close();

    }

    public function writeShow(Array $liste_id, EntityShowsRepository $esr)
    {
        // Création Writer XLSX
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile("export_selectif.xlsx");

        //Création des champs dynamique
        $mongoman = new MongoManager();
        $dynArray = array();
        foreach ($liste_id as $id) {
            $shows = $esr->find($id);
            $array = $mongoman->getDocById("Entity_show_sheet",$shows->getSheetId());
            array_splice($array,0,1);
            foreach($array as $key=>$value){
                if (!in_array($key,$dynArray))
                    $dynArray[] = $key;
            }
        }

        // Création première ligne avec noms de colonnes
        $firstLineCells = ["nom", "année"];
        $firstLineCells = array_merge($firstLineCells,$dynArray);
        $firstRow = WriterEntityFactory::createRowFromArray($firstLineCells);
        $writer->addRow($firstRow);

        foreach ($liste_id as $id) {
            $institution = $esr->find($id);
            $rowcells = [$shows->getName(),
                         $shows->getYear()];

            // AJOUT DES VALEURS MONGODB
            $array = $mongoman->getDocById("Entity_show_sheet",$shows->getSheetId());
            array_splice($array,0,1);
            foreach($array as $key=>$value){
                for($i=2;sizeof($firstLineCells)>$i;$i++){
                    if (!isset($rowcells[$i])){
                        $rowcells[$i] = ''; // Mise à vide dans le cas où il n'y a pas de valeur
                    }
                    if ($firstLineCells[$i]==$key)
                        $rowcells[$i] = $value;
                }
            }
            $row = WriterEntityFactory::createRowFromArray($rowcells);
            $writer->addRow($row);
        }

        $writer->close();

    }

    public function writeModelPersonne()
    {
        // Création Writer XLSX
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile("modele_personne.xlsx");

        // Création première ligne avec noms de colonnes
        $firstLineCells = ["Nom", "Prénom", "Date de naissance", "Code postal", "Ville", "Abonné à la newsletter", "Adresse mail", "Institution"];
        $firstLineCells = array_merge($firstLineCells);
        $firstRow = WriterEntityFactory::createRowFromArray($firstLineCells);
        $writer->addRow($firstRow);
        $writer->close();
    }

    public function writeModelInstitution()
    {
        // Création Writer XLSX
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile("modele_institution.xlsx");

        // Création première ligne avec noms de colonnes
        $firstLineCells = ["Nom", "Rôle"];
        $firstLineCells = array_merge($firstLineCells);
        $firstRow = WriterEntityFactory::createRowFromArray($firstLineCells);
        $writer->addRow($firstRow);
        $writer->close();
    }

    public function writeModelSpectacle()
    {
        // Création Writer XLSX
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile("modele_spectacle.xlsx");

        // Création première ligne avec noms de colonnes
        $firstLineCells = ["Nom", "Année"];
        $firstLineCells = array_merge($firstLineCells);
        $firstRow = WriterEntityFactory::createRowFromArray($firstLineCells);
        $writer->addRow($firstRow);
        $writer->close();
    }

    public function writeCustomModele($name,$id_sheet,$data)
    {
        // Création Writer XLSX
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile("$name.xlsx");

        // Création première ligne avec noms de colonnes
        $firstLineCells = $data;
        $firstLineCells = array_merge($firstLineCells);
        $firstRow = WriterEntityFactory::createRowFromArray($firstLineCells);
        $id = WriterEntityFactory::createRowFromArray(array("id",$id_sheet));
        $writer->addRow($id);
        $writer->addRow($firstRow);
        $writer->close();
    }

}
