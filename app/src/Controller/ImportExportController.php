<?php

namespace App\Controller;

use App\Entity\EntityInstitutions;
use App\Entity\EntityModele;
use App\Entity\EntityShows;
use App\Entity\EntityUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $userId = $this->getUser()->getId();

        $modele_repo = $this->getDoctrine()->getRepository(EntityModele::class);
        return $this->render('import_export/index.html.twig',[
            'modeles' => $modele_repo->findByUserIdJoinToUser($userId)
        ]);
    }

    /**
     * @Route("/export", name="export")
     * @param EntityPeopleRepository $epr
     */
    public function export(EntityPeopleRepository $epr)
    {

	if($this->isGranted('EXPORT','')){
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
	else{
		return $this->render('error403forbidden.html.twig');
	}
    }

    /**
     * @Route("/export_selectif", name="export_selectif")
     */
    public function export_selectif()
    {
	if($this->isGranted('EXPORT',$institution_id)){
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
	else{
		return $this->render('error403forbidden.html.twig');
	}
    }

    /**
     * @Route("/export_institutions", name="export_institutions")
     */
    public function export_institution(Request $request)
    {
        $institution = $this->getDoctrine()->getRepository(EntityInstitutions::class);

        $writer = new XLSXWriter();

        $writer->writeInstitution($_POST['ids'], $institution);

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
     * @Route("/export_shows", name="export_shows")
     */
    public function export_shows(Request $request)
    {
        $shows = $this->getDoctrine()->getRepository(EntityShows::class);

        $writer = new XLSXWriter();

        $writer->writeShow($_POST['ids'], $shows);

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
     * @param Request $request
     * @return RedirectResponse
     */
    public function import(Request $request)
    {
        $fichier = basename($_FILES['import']['name']);
        $taille = filesize($_FILES['import']['tmp_name']);
        $extensions = array('.xlsx', '.ods', '.csv');
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
                $reader = new XLSXReader($this->getDoctrine()->getManager(), $this->getUser());
                $emp = $this->getDoctrine()->getRepository(EntityPeople::class);
                // mettre l'appel à la fonction ici
                if($reader->readFirstLine($fichier) == 'id'){
                    $reader->readCustomSheet($request, $fichier, $emp);
                }else{
                    $reader->readAll($request, $fichier);
                }
                return $this->redirectToRoute('entity_people_index');
            }
            else //Sinon (la fonction renvoie FALSE).
            {
                return $this->redirectToRoute("import_export",['error'=>'Echec de l\'upload !']);
            }
        }
        else
        {
            return $this->redirectToRoute("import_export",['error'=>$erreur]);
        }
    }

    /**
     * @Route("/modele_personne", name="modele_personne")
     *
     */
    public function modele_personne(Request $request)
    {
        $people = $this->getDoctrine()->getRepository(EntityPeople::class);

        $writer = new XLSXWriter();

        $writer->writeModelPersonne();

        $file = "modele_personne.xlsx";
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
     * @Route("/modele_institution", name="modele_institution")
     */
    public function modele_institution(Request $request)
    {
        $institution = $this->getDoctrine()->getRepository(EntityInstitutions::class);

        $writer = new XLSXWriter();

        $writer->writeModelInstitution();
        $institution_id = null;
        
        if($this->isGranted('IMPORT',$institution_id)){
            $fichier = basename($_FILES['import']['name']);
            $taille = filesize($_FILES['import']['tmp_name']);
            $extensions = array('.xlsx', '.ods', '.csv');
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
		            $reader = new XLSXReader($this->getDoctrine()->getManager(), $this->getUser());
		            $reader->readAll($request, $fichier);
		            return $this->redirectToRoute('entity_people_index');
                }
                else //Sinon (la fonction renvoie FALSE).
		        {
		            return $this->redirectToRoute("import_export",['error'=>'Echec de l\'upload !']);
		        }
            }
            else
		    {
		        return $this->redirectToRoute("import_export",['error'=>$erreur]);
		    }
	    }
	    else{
		    return $this->render('error403forbidden.html.twig');
	    }
            $file = "modele_institution.xlsx";
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
     * @Route("/modele_spectacle", name="modele_spectacle")
     */
    public function modele_spectacle(Request $request)
    {
        $show = $this->getDoctrine()->getRepository(EntityShows::class);

        $writer = new XLSXWriter();

        $writer->writeModelSpectacle();

        $file = "modele_spectacle.xlsx";
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
}
