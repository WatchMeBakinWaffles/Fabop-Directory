<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\EntityPeople;

use App\Repository\EntityPeopleRepository;
use App\Utils\XLSXWriter;

use App\Utils\XLSXReader;

/**
 * @Route("manager/imp-exp")
 */
class ImportExportController extends AbstractController
{
    /**
     * @Route("/", name="import_export")
     */
    public function index()
    {
        return $this->render('import_export/index.html.twig');
    }

    /**
     * @Route("/export", name="export")
     */
    public function export(EntityPeopleRepository $epr)
    {

        $institution_id = null;

        if(!in_array('ROLE_ADMIN', $this->getUser()->getRoles()))        
            $institution_id = $this->getUser()->getInstitution();        


        $writer = new XLSXWriter();
        $writer->writeAll($epr, $institution_id);

        $file = "export.xlsx";
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }

    /**
     * @Route("/export_selectif", name="export_selectif")
     */
    public function export_selectif(Request $request)
    {
        $people = $this->getDoctrine()->getRepository(EntityPeople::class);

        $writer = new XLSXWriter();

        $writer->write($_POST['ids'], $people);

        $file = "export_selectif.xlsx";
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }

    /**
     * @Route("/import", name="import")
     */
    public function import(Request $request)
    {
        $fichier = basename($_FILES['import']['name']);
        $taille = filesize($_FILES['import']['tmp_name']);
        $extensions = array('.xlsx');
        $extension = strrchr($_FILES['import']['name'], '.');
        //Début des vérifications de sécurité...
        if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
        {
            $erreur = 'Vous devez uploader un fichier de type xlsx';
        }
        if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
        {
            //On formate le nom du fichier ici...
            $fichier = strtr($fichier,
                'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
                'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
            $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
            if(move_uploaded_file($_FILES['import']['tmp_name'], $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
            {
                $reader = new XLSXReader($this->getDoctrine()->getManager());
                $reader->readAll($request, $fichier);
                return $this->redirectToRoute('entity_people_index');
            }
            else //Sinon (la fonction renvoie FALSE).
            {
                echo 'Echec de l\'upload !';
            }
        }
        else
        {
            echo $erreur;
        }


    }
}
