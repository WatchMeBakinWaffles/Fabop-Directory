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
     * @Route("/dashboard", name="dashboard", methods="GET")
     */
    public function index(): Response
    {
        return $this->render('baselayout.html.twig');
    }

}