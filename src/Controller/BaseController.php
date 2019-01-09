<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\MongoManager;

/**
 * @Route("/")
 */
class BaseController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index(): Response
    {
        //filtres à appliquer ici
        return $this->render('baselayout.html.twig');
    }

}