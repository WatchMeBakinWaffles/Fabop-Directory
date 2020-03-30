<?php

namespace App\Controller;

use App\Entity\EntityUser;
use App\Form\EntityUserType;
use App\Repository\EntityUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/users")
 */
class EntityUserController extends AbstractController
{
    /**
     * @Route("/", name="admin_user_index", methods={"GET"})
     */
    public function index(EntityUserRepository $entityUserRepository): Response
    {
        return $this->render('entity_user/index.html.twig', [
            'entity_users' => $entityUserRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $entityUser = new EntityUser();
        $form = $this->createForm(EntityUserType::class, $entityUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entityUser);
            /**
            * Hashage du mot de passe avec le protocole BCRYPT juste avant l'enregistrement en bd.
            */
            $entityUser->bCryptPassword($entityUser->getPassword());
            if(!$this->isGranted('ROLE_ADMIN'))
                $entityUser->setInstitution($this->getUser()->getInstitution());
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('entity_user/new.html.twig', [
            'entity_user' => $entityUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_show", methods={"GET"})
     */
    public function show(EntityUser $entityUser): Response
    {
        return $this->render('entity_user/show.html.twig', [
            'entity_user' => $entityUser,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EntityUser $entityUser): Response
    {
        $form = $this->createForm(EntityUserType::class, $entityUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entityUser);
            /**
             * Hashage du mot de passe avec le protocole BCRYPT juste avant l'enregistrement en bd.
             */
            $entityUser->bCryptPassword($entityUser->getPassword());
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('entity_user/edit.html.twig', [
            'entity_user' => $entityUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EntityUser $entityUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entityUser->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entityUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_index');
    }
}
