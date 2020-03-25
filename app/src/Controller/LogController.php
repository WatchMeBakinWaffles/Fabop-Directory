<?php

namespace App\Controller;

use App\Entity\Log;
use App\Form\LogType;
use App\Repository\LogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/log")
 */
class LogController extends AbstractController
{
    /**
     * @Route("/", name="log_index", methods={"GET"})
     */
    public function index(LogRepository $logRepository): Response
    {
        return $this->render('log/index.html.twig', [
            'logs' => $logRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="log_new", methods={"GET","POST"})
     */
    public function new(array $data)
    {
        $log = new Log();
        $log->setDate($data['date']);
        $log->setElement($data['element']);
        $log->setTypeAction($data['type_action']);
        $log->setComment($data['comment']);
        $log->setIdUser($data['id_user']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($log);
        $entityManager->flush();

    }

    /**
     * @Route("/{id}", name="log_show", methods={"GET"})
     */
    public function show(Log $log): Response
    {
        return $this->render('log/show.html.twig', [
            'log' => $log,
        ]);
    }

    /**
     * @Route("/{id}", name="log_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Log $log): Response
    {
        if ($this->isCsrfTokenValid('delete'.$log->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($log);
            $entityManager->flush();
        }

        return $this->redirectToRoute('log_index');
    }
}
