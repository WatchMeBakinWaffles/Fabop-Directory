<?php

namespace App\Utils;

use App\Entity\EntityPeople;
use App\Repository\EntityPeopleRepository;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

class XLSXWriter
{

    public function writeAll(EntityPeopleRepository $entityPeopleRepository)
    {
        // Création Writer XLSX
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile("export.xlsx");

        // Récupération participants
        $people = $entityPeopleRepository->findAll();

        // Création première ligne avec noms de colonnes
        $firstLineCells = ["Nom", "Prénom", "Date de naissance", "Code postal", "Ville", "Institution", "Abonné à la newsletter"];
        $firstRow = WriterEntityFactory::createRowFromArray($firstLineCells);
        $writer->addRow($firstRow);

        // Ajout de toutes les personnes dans l'excel
        foreach ($people as $person) {
            $rowcells = [$person->getName(),
                         $person->getFirstname(),
                         $person->getBirthdate()->format("j-m-Y"),
                         $person->getPostalcode(),
                         $person->getCity(),
                         $person->getInstitution()->getName(),
                         $person->getNewsletter()];
            $row = WriterEntityFactory::createRowFromArray($rowcells);
            $writer->addRow($row);
        }

        $writer->close();

    }

}