<?php

namespace App\Controller;

use App\Entity\EntityUser;
use App\Form\EntityUserType;
use App\Repository\EntityUserRepository;

use App\Entity\EntityRoles;
use App\Form\EntityRolesType;
use App\Repository\EntityRolesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/")
 */
class EntityRolesController extends AbstractController
{    
    /**
    * @Route("/users/", name="admin_roles_index", methods={"GET"})
    */
   public function index(EntityRolesRepository $entityRolesRepository): Response
   {
       return $this->render('entity_roles/index.html.twig', [
        'entity_roles' => $entityRolesRepository->findAll(),
       ]);
   }
    /**
     * @Route("/", name="admin_user_index", methods={"GET"})
     */
    public function index_to_users_list(EntityUserRepository $entityUserRepository): Response
    {
        return $this->render('entity_user/index.html.twig', [
            'entity_users' => $entityUserRepository->findAll(),
        ]);
    }

    /**
     * @Route("roles/new", name="admin_roles_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $entityRoles = new EntityRoles();
        $form = $this->createForm(EntityRolesType::class, $entityRoles);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entityRoles);
            $entityManager->flush();

            return $this->redirectToRoute('admin_roles_index');
        }

        return $this->render('entity_roles/new.html.twig', [
            'entity_roles' => $entityRoles,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("roles/{id}", name="admin_roles_show", methods={"GET"})
     */
    public function show(EntityRoles $entityRoles): Response
    {
        return $this->render('entity_roles/show.html.twig', [
            'entity_roles' => $entityRoles,
        ]);
    }

    /**
     * @Route("roles/{id}/edit", name="admin_roles_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EntityRoles $entityRoles): Response
    {
        $form = $this->createForm(EntityRolesType::class, $entityRoles);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entityRoles);
            $entityManager->flush();

            return $this->redirectToRoute('admin_roles_index');
        }

        return $this->render('entity_roles/edit.html.twig', [
            'entity_roles' => $entityRoles,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("roles/{id}", name="admin_roles_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EntityRoles $entityRoles): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entityRoles->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entityRoles);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_roles_index');
    }
}
