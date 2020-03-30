<?php

namespace App\Utils;

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

    public function __construct(EntityManagerInterface $em, $user){
        $this->em = $em;
        $this->user = $user;
    }

    public function readAll(Request $request, String $filename)
    {

        $mongoman = new MongoManager();

        $reader = ReaderEntityFactory::createReaderFromFile($filename);

        $reader->open($filename);

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                if ($rowNumber > 1) {
                    $cells = $row->getCells();
                    $entityPerson = new EntityPeople();
                    $entityInstitutions = new EntityInstitutions();
                    if (null != $request->request->get('person_data')){
                        $sheetId=$mongoman->insertSingle("Entity_person_sheet",$request->request->get('person_data'));
                    }else{
                        $sheetId=$mongoman->insertSingle("Entity_person_sheet",[]);
                    }

                    // Mise en bdd MySQL des données venu d'excel
                    //Name
                    $entityPerson->setName($cells[0]->getValue());

                    // FirstName
                    $entityPerson->setFirstname($cells[1]->getValue());

                    // BirthDate
                    $date = new \DateTime($cells[2]->getValue());
                    $entityPerson->setBirthdate($date);
                    
                    // postal_code
                    $entityPerson->setPostalCode($cells[3]->getValue());
                    
                    // city
                    $entityPerson->setCity($cells[4]->getValue());
                    
                    // newsletter
                    $entityPerson->setNewsletter($cells[5]->getValue());

                    // adresse_mailing
                    $entityPerson->setAdresseMailing($cells[6]->getValue());

                    // add_date
                    $entityPerson->setAddDate(new \DateTime("now"));

                    //sheet_id
                    $entityPerson->setSheetId($sheetId);

                    if(in_array('ROLE_ADMIN', $this->user->getRoles())){
                        // institution_id
                        $institut = $this->em->getRepository(EntityInstitutions::class)
                                         ->findOneByName($cells[7]->getValue());
    
                        
                        // Si l'institut n'existe pas (null), on la crée
                        if ($institut == null){
                            $entityInstitutions->setName($cells[7]->getValue());
                            $entityInstitutions->setRole("Ceci est une institution");
    
                            if (null != $request->request->get('institution_data')){
                                $sheetID=$mongoman->insertSingle("Entity_institution_sheet",$request->request->get('institution_data'));
                            }else{
                                $sheetID=$mongoman->insertSingle("Entity_institution_sheet",[]);
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
                    
                    $this->em->persist($entityPerson);
                    $this->em->flush();
                }
            }
        }

        $reader->close();

    }

}