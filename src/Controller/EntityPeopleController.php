<?php

namespace App\Controller;

use App\Entity\EntityPeople;
use App\Form\EntityPeopleType;
use App\Repository\EntityPeopleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entity/people")
 */
class EntityPeopleController extends AbstractController
{
    /**
     * @Route("/", name="entity_people_index", methods="GET")
     */
    public function index(EntityPeopleRepository $entityPeopleRepository): Response
    {
        return $this->render('entity_people/index.html.twig', ['entity_peoples' => $entityPeopleRepository->findAll()]);
    }

    /**
     * @Route("/new", name="entity_people_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $entityPerson = new EntityPeople();
        $form = $this->createForm(EntityPeopleType::class, $entityPerson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entityPerson);
            $em->flush();

            return $this->redirectToRoute('entity_people_index');
        }

        return $this->render('entity_people/new.html.twig', [
            'entity_person' => $entityPerson,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entity_people_show", methods="GET")
     */
    public function show(EntityPeople $entityPerson): Response
    {
        return $this->render('entity_people/show.html.twig', ['entity_person' => $entityPerson]);
    }

    /**
     * @Route("/{id}/edit", name="entity_people_edit", methods="GET|POST")
     */
    public function edit(Request $request, EntityPeople $entityPerson): Response
    {
        $form = $this->createForm(EntityPeopleType::class, $entityPerson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entity_people_index', ['id' => $entityPerson->getId()]);
        }

        return $this->render('entity_people/edit.html.twig', [
            'entity_person' => $entityPerson,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entity_people_delete", methods="DELETE")
     */
    public function delete(Request $request, EntityPeople $entityPerson): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entityPerson->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entityPerson);
            $em->flush();
        }

        return $this->redirectToRoute('entity_people_index');
    }
}
