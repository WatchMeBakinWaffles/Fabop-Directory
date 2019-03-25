<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;


class SpoutController extends AbstractController
{
    /**
     * @Route("/", name="spout_index")
     */
    public function index()
    {
        return $this->render('spout/index.html.twig', [
            'controller_name' => 'SpoutController',
        ]);
    }

    /**
     * @Route("/spout", name="spout_read")
     */
    public function readTable() {

    	$test = dirname(__FILE__);
    	$test = scandir("/var/www/html/symfony/");
    	$filePath = '../assets/test.csv';
    	$reader = ReaderFactory::create(Type::CSV);
    	$reader->open($filePath);

    	foreach($reader->getSheetIterator() as $sheet) {
    		foreach ($sheet->getRowIterator() as $rowIndex => $row ) {
    			$allRows[] = $row;
    		}
    	}

    	$reader->close();
    	var_dump($allRows);
    	return $allRows;
    	// return $this->render('spout/index.html.twig', [
     //        'controller_name' => $test,
     //    ]);
    }
}
