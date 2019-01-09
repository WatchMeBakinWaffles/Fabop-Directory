<?php

namespace App\Controller;

use App\Entity\TagsAffect;
use App\Form\TagsAffectType;
use App\Repository\TagsAffectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tags/affect")
 */
class TagsAffectController extends AbstractController
{
    /**
     * @Route("/", name="tags_affect_index", methods="GET")
     */
    public function index(TagsAffectRepository $tagsAffectRepository): Response
    {
        //filtres à appliquer ici
        return $this->render('tags_affect/index.html.twig', ['tags_affects' => $tagsAffectRepository->findAll()]);
    }

    /**
     * @Route("/new", name="tags_affect_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $tagsAffect = new TagsAffect();
        $form = $this->createForm(TagsAffectType::class, $tagsAffect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tagsAffect);
            $em->flush();

            return $this->redirectToRoute('tags_affect_index');
        }

        return $this->render('tags_affect/new.html.twig', [
            'tags_affect' => $tagsAffect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tags_affect_show", methods="GET")
     */
    public function show(TagsAffect $tagsAffect): Response
    {
        return $this->render('tags_affect/show.html.twig', ['tags_affect' => $tagsAffect]);
    }

    /**
     * @Route("/{id}/edit", name="tags_affect_edit", methods="GET|POST")
     */
    public function edit(Request $request, TagsAffect $tagsAffect): Response
    {
        $form = $this->createForm(TagsAffectType::class, $tagsAffect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tags_affect_index', ['id' => $tagsAffect->getId()]);
        }

        return $this->render('tags_affect/edit.html.twig', [
            'tags_affect' => $tagsAffect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tags_affect_delete", methods="DELETE")
     */
    public function delete(Request $request, TagsAffect $tagsAffect): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tagsAffect->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tagsAffect);
            $em->flush();
        }

        return $this->redirectToRoute('tags_affect_index');
    }
}
