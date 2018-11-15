<?php

namespace App\Controller;

use App\Entity\EntityTags;
use App\Form\EntityTagsType;
use App\Repository\EntityTagsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entity/tags")
 */
class EntityTagsController extends AbstractController
{
    /**
     * @Route("/", name="entity_tags_index", methods="GET")
     */
    public function index(EntityTagsRepository $entityTagsRepository): Response
    {
        return $this->render('entity_tags/index.html.twig', ['entity_tags' => $entityTagsRepository->findAll()]);
    }

    /**
     * @Route("/new", name="entity_tags_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $entityTag = new EntityTags();
        $form = $this->createForm(EntityTagsType::class, $entityTag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entityTag);
            $em->flush();

            return $this->redirectToRoute('entity_tags_index');
        }

        return $this->render('entity_tags/new.html.twig', [
            'entity_tag' => $entityTag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entity_tags_show", methods="GET")
     */
    public function show(EntityTags $entityTag): Response
    {
        return $this->render('entity_tags/show.html.twig', ['entity_tag' => $entityTag]);
    }

    /**
     * @Route("/{id}/edit", name="entity_tags_edit", methods="GET|POST")
     */
    public function edit(Request $request, EntityTags $entityTag): Response
    {
        $form = $this->createForm(EntityTagsType::class, $entityTag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entity_tags_index', ['id' => $entityTag->getId()]);
        }

        return $this->render('entity_tags/edit.html.twig', [
            'entity_tag' => $entityTag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entity_tags_delete", methods="DELETE")
     */
    public function delete(Request $request, EntityTags $entityTag): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entityTag->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entityTag);
            $em->flush();
        }

        return $this->redirectToRoute('entity_tags_index');
    }
}
