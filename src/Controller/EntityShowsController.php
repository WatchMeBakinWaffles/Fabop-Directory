<?php

namespace App\Controller;

use App\Entity\EntityShows;
use App\Form\EntityShowsType;
use App\Repository\EntityShowsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entity/shows")
 */
class EntityShowsController extends AbstractController
{
    /**
     * @Route("/", name="entity_shows_index", methods="GET")
     */
    public function index(EntityShowsRepository $entityShowsRepository): Response
    {
        return $this->render('entity_shows/index.html.twig', ['entity_shows' => $entityShowsRepository->findAll()]);
    }

    /**
     * @Route("/new", name="entity_shows_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $entityShow = new EntityShows();
        $form = $this->createForm(EntityShowsType::class, $entityShow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entityShow);
            $em->flush();

            return $this->redirectToRoute('entity_shows_index');
        }

        return $this->render('entity_shows/new.html.twig', [
            'entity_show' => $entityShow,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entity_shows_show", methods="GET")
     */
    public function show(EntityShows $entityShow): Response
    {
        return $this->render('entity_shows/show.html.twig', ['entity_show' => $entityShow]);
    }

    /**
     * @Route("/{id}/edit", name="entity_shows_edit", methods="GET|POST")
     */
    public function edit(Request $request, EntityShows $entityShow): Response
    {
        $form = $this->createForm(EntityShowsType::class, $entityShow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entity_shows_index', ['id' => $entityShow->getId()]);
        }

        return $this->render('entity_shows/edit.html.twig', [
            'entity_show' => $entityShow,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entity_shows_delete", methods="DELETE")
     */
    public function delete(Request $request, EntityShows $entityShow): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entityShow->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entityShow);
            $em->flush();
        }

        return $this->redirectToRoute('entity_shows_index');
    }
}
