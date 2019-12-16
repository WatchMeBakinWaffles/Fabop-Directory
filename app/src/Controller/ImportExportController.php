<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("manager/import")
 */
class ImportExportController extends AbstractController
{
    /**
     * @Route("/", name="manager/import_export")
     */
    public function index()
    {
        return $this->render('import_export/index.html.twig', [
            'controller_name' => 'ImportExportController',
        ]);
    }
}
