<?php

namespace App\Controller;

use App\Entity\EntityInstitutions;
use App\Form\EntityInstitutionsType;
use App\Repository\EntityInstitutionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entity/institutions")
 */
class EntityInstitutionsController extends AbstractController
{
    /**
     * @Route("/", name="entity_institutions_index", methods="GET")
     */
    public function index(EntityInstitutionsRepository $entityInstitutionsRepository): Response
    {
        return $this->render('entity_institutions/index.html.twig', ['entity_institutions' => $entityInstitutionsRepository->findAll()]);
    }

    /**
     * @Route("/new", name="entity_institutions_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $entityInstitution = new EntityInstitutions();
        $form = $this->createForm(EntityInstitutionsType::class, $entityInstitution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entityInstitution);
            $em->flush();

            return $this->redirectToRoute('entity_institutions_index');
        }

        return $this->render('entity_institutions/new.html.twig', [
            'entity_institution' => $entityInstitution,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entity_institutions_show", methods="GET")
     */
    public function show(EntityInstitutions $entityInstitution): Response
    {
        return $this->render('entity_institutions/show.html.twig', ['entity_institution' => $entityInstitution]);
    }

    /**
     * @Route("/{id}/edit", name="entity_institutions_edit", methods="GET|POST")
     */
    public function edit(Request $request, EntityInstitutions $entityInstitution): Response
    {
        $form = $this->createForm(EntityInstitutionsType::class, $entityInstitution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entity_institutions_index', ['id' => $entityInstitution->getId()]);
        }

        return $this->render('entity_institutions/edit.html.twig', [
            'entity_institution' => $entityInstitution,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entity_institutions_delete", methods="DELETE")
     */
    public function delete(Request $request, EntityInstitutions $entityInstitution): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entityInstitution->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entityInstitution);
            $em->flush();
        }

        return $this->redirectToRoute('entity_institutions_index');
    }
}
