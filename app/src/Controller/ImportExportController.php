<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\EntityPeopleRepository;
use App\Utils\XLSXWriter;

/**
 * @Route("manager/imp-exp")
 */
class ImportExportController extends AbstractController
{
    /**
     * @Route("/", name="manager/import_export")
     */
    public function index()
    {
        return $this->render('import_export/index.html.twig');
    }

    /**
     * @Route("/export", name="manager/export")
     */
    public function export(EntityPeopleRepository $epr)
    {
        $writer = new XLSXWriter();
        $writer->writeAll($epr);

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
}
