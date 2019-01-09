<?php

namespace App\Controller;

use App\Entity\EntityPerformances;
use App\Form\EntityPerformancesType;
use App\Repository\EntityPerformancesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/manager/performances")
 */
class EntityPerformancesController extends AbstractController
{
    /**
     * @Route("/", name="entity_performances_index", methods="GET")
     */
    public function index(EntityPerformancesRepository $entityPerformancesRepository): Response
    {
        //filtres à appliquer ici
        return $this->render('entity_performances/index.html.twig', ['entity_performances' => $entityPerformancesRepository->findAll()]);
    }

    /**
     * @Route("/new", name="entity_performances_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $entityPerformance = new EntityPerformances();
        $form = $this->createForm(EntityPerformancesType::class, $entityPerformance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entityPerformance);
            $em->flush();

            return $this->redirectToRoute('entity_performances_index');
        }

        return $this->render('entity_performances/new.html.twig', [
            'entity_performance' => $entityPerformance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entity_performances_show", methods="GET")
     */
    public function show(EntityPerformances $entityPerformance): Response
    {
        return $this->render('entity_performances/show.html.twig', ['entity_performance' => $entityPerformance]);
    }

    /**
     * @Route("/{id}/edit", name="entity_performances_edit", methods="GET|POST")
     */
    public function edit(Request $request, EntityPerformances $entityPerformance): Response
    {
        $form = $this->createForm(EntityPerformancesType::class, $entityPerformance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entity_performances_index', ['id' => $entityPerformance->getId()]);
        }

        return $this->render('entity_performances/edit.html.twig', [
            'entity_performance' => $entityPerformance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entity_performances_delete", methods="DELETE")
     */
    public function delete(Request $request, EntityPerformances $entityPerformance): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entityPerformance->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entityPerformance);
            $em->flush();
        }

        return $this->redirectToRoute('entity_performances_index');
    }
}
