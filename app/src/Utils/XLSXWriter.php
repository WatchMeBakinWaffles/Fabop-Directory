<?php

namespace App\Utils;

use App\Repository\EntityInstitutionsRepository;
use App\Repository\EntityPeopleRepository;
use App\Repository\EntityShowsRepository;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class XLSXWriter
{

    public function writeAll(EntityPeopleRepository $entityPeopleRepository, $institution_id = null)
    {


        // // Création Writer XLS
        // $writer = WriterEntityFactory::createXLSXWriter();
        // $writer->openToFile("export.xlsx");

        /**
         * phpSpreadSheet
         * creation d'une nouvelle feuille
         * activaiton de la protection des cellules
         */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getProtection()->setSheet(true);
        $sheet->protectCells('A1:Z1', 'admin');
        $sheet->getStyle('A2:Z500')
            ->getProtection()
            ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);

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
        // $firstRow = WriterEntityFactory::createRowFromArray($firstLineCells);
        // $writer->addRow($firstRow);

        /**
         * phpSpreadSheet
         * insertion des nom des champs dans la premiere ligne de la feuille
         */
        $sheet->fromArray($firstLineCells);

        // Ajout de toutes les personnes dans l'excel
        $count = 2; // initialisation d'un compteur pour l'insertion ligne par ligne des exports
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

            /**
             * phpSpreadSheet
             * insertion des lignes export
             * utilisation d'un compteur pour decaler la ligne d'insertion (sinon même ligne ecraser à chaque itération)
             */
            $sheet->fromArray($rowcells, NULL, 'A'.$count);
            $count++;

            // $row = WriterEntityFactory::createRowFromArray($rowcells);
            // $writer->addRow($row);
        }

        // $writer->close();

        /**
         * phpSpreadSheet
         * creation de la feuilel au format xlsx
         */
        $writer = new Xlsx($spreadsheet);
        $writer->save('export_all.xlsx');

    }

    public function write(Array $liste_id, EntityPeopleRepository $epr)
    {
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getProtection()->setSheet(true);
        $sheet->protectCells('A1:Z1', 'admin');
        $sheet->getStyle('A2:Z500')
            ->getProtection()
            ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);

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
        $sheet->fromArray($firstLineCells);

        $count = 2;
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
                    if ($firstLineCells[$i]==$key){
                        $rowcells[$i] = $value;
                    }
                }
            }

            $sheet->fromArray($rowcells, NULL, 'A'.$count);
            $count++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save('export_selectif.xlsx');
    }

    public function writeInstitution(Array $liste_id, EntityInstitutionsRepository $eir)
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getProtection()->setSheet(true);
        $sheet->protectCells('A1:Z1', 'admin');
        $sheet->getStyle('A2:Z500')
            ->getProtection()
            ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);

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
        $sheet->fromArray($firstLineCells);

        $count = 2;
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
            $sheet->fromArray($rowcells, NULL, 'A'.$count);
            $count++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save('export_institution.xlsx');

    }

    public function writeShow(Array $liste_id, EntityShowsRepository $esr)
    {
        // Création Writer XLSX
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getProtection()->setSheet(true);
        $sheet->protectCells('A1:Z1', 'admin');
        $sheet->getStyle('A2:Z500')
            ->getProtection()
            ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);

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
        $sheet->fromArray($firstLineCells);

        $count = 2;
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
            $sheet->fromArray($rowcells, NULL, 'A'.$count);
            $count++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save('export_shows.xlsx');

    }

    public function writeModelPersonne()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getProtection()->setSheet(true);
        $sheet->protectCells('A1:Z1', 'admin');
        $sheet->getStyle('A2:Z500')
            ->getProtection()
            ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);

        // Création première ligne avec noms de colonnes
        $firstLineCells = ["Nom", "Prénom", "Date de naissance", "Code postal", "Ville", "Abonné à la newsletter", "Adresse mail", "Institution"];
        $firstLineCells = array_merge($firstLineCells);
        $sheet->fromArray($firstLineCells);
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('modele_personne.xlsx');
    }

    public function writeModelInstitution()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getProtection()->setSheet(true);
        $sheet->protectCells('A1:Z1', 'admin');
        $sheet->getStyle('A2:Z500')
            ->getProtection()
            ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);

        // Création première ligne avec noms de colonnes
        $firstLineCells = ["Nom", "Rôle"];
        $firstLineCells = array_merge($firstLineCells);
        $sheet->fromArray($firstLineCells);

        $writer = new Xlsx($spreadsheet);
        $writer->save('modele_institution.xlsx');
    }

    public function writeModelSpectacle()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getProtection()->setSheet(true);
        $sheet->protectCells('A1:Z1', 'admin');
        $sheet->getStyle('A2:Z500')
            ->getProtection()
            ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);

        // Création première ligne avec noms de colonnes
        $firstLineCells = ["Nom", "Année"];
        $firstLineCells = array_merge($firstLineCells);
        $sheet->fromArray($firstLineCells);

        $writer = new Xlsx($spreadsheet);
        $writer->save('model_spectacle.xlsx');
    }

    public function writeCustomModele($name,$id_sheet,$data)
    {
        // Création Writer XLSX
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile("$name.xlsx");

        // Création première ligne avec noms de colonnes
        $firstLineCells = $data;
        $firstLineCells = array_merge($firstLineCells);
        $border = (new BorderBuilder())
            ->setBorderRight(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->build();
        $style = (new StyleBuilder())
            ->setBorder($border)
            ->setShouldWrapText(true)
            ->setBackgroundColor(Color::RED)
            ->build();
        $firstRow = WriterEntityFactory::createRowFromArray($firstLineCells,$style);
        $id = WriterEntityFactory::createRowFromArray(array("id",$id_sheet,"NE PAS MODIFIER CE QUI EST EN ROUGE"),$style);
        $writer->addRow($id);
        $writer->addRow($firstRow);
        $writer->close();
    }

}
