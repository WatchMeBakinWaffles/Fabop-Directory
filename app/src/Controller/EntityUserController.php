<?php

namespace App\Controller;

use App\Entity\EntityRoles;
use App\Form\EntityRolesType;
use App\Repository\EntityRolesRepository;
use App\Repository\PermissionsRepository;
use App\Entity\EntityUser;
use App\Form\EntityUserType;
use App\Repository\EntityUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\MongoManager;


/**
 * @Route("/admin/")
 */
class EntityUserController extends AbstractController
{
    /**
     * @Route("users/", name="admin_user_index", methods={"GET"})
     */
    public function index(EntityUserRepository $entityUserRepository): Response
    {
        return $this->render('entity_user/index.html.twig', [
            'entity_users' => $entityUserRepository->findAll(),
        ]);
    }
    /**
     * @Route("roles/", name="admin_roles_index", methods={"GET"})
     */
    public function index_to_list_roles(EntityRolesRepository $entityRolesRepository): Response
    {
		$mongoman = new MongoManager();
		$data_permissions = $mongoman->getDocById("permissions_user","5fb6aa76ff0f877fab155dd3");
        return $this->render('entity_roles/index.html.twig', [
			'entity_roles' => $entityRolesRepository->findAll(),
			'trey' => $data_permissions,
        ]);
    }
    /**
     * @Route("users/new", name="admin_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $entityUser = new EntityUser();

	if(!$this->isGranted('POST_EDIT',$entityUser)){

		$form = $this->createForm(EntityUserType::class, $entityUser);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
		    $entityManager = $this->getDoctrine()->getManager();
		    $entityManager->persist($entityUser);
		    /**
		    * Hashage du mot de passe avec le protocole BCRYPT juste avant l'enregistrement en bd.
		    */
		    $entityUser->bCryptPassword($entityUser->getPassword());
		    if(in_array('ROLE_ADMIN', $entityUser->getRoles()))
		        $entityUser->setInstitution(NULL);
		    $entityManager->flush();

		    return $this->redirectToRoute('admin_user_index');
		}

		return $this->render('entity_user/new.html.twig', [
		    'entity_user' => $entityUser,
		    'form' => $form->createView(),
		]);
	}
	return $this->redirectToRoute('admin_user_index');
    }

    /**
     * @Route("users/{id}", name="admin_user_show", methods={"GET"})
     */
    public function show(EntityUser $entityUser): Response
    {
	if(!$this->isGranted('POST_VIEW',$entityUser)){

		return $this->render('entity_user/show.html.twig', [
		    'entity_user' => $entityUser,
		]);
	}
	return $this->redirectToRoute('admin_user_index');
    }

    /**
     * @Route("users/{id}/edit", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EntityUser $entityUser): Response
    {
	if(!$this->isGranted('POST_EDIT',$entityUser)){

		$form = $this->createForm(EntityUserType::class, $entityUser);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
		    $entityManager = $this->getDoctrine()->getManager();
		    $entityManager->persist($entityUser);
		    /**
		     * Hashage du mot de passe avec le protocole BCRYPT juste avant l'enregistrement en bd.
		     */
		    $entityUser->bCryptPassword($entityUser->getPassword());
		    if(in_array('ROLE_ADMIN', $entityUser->getRoles()))
		        $entityUser->setInstitution(NULL);
		    $entityManager->flush();

		    return $this->redirectToRoute('admin_user_index');
		}

		return $this->render('entity_user/edit.html.twig', [
		    'entity_user' => $entityUser,
		    'form' => $form->createView(),
		]);
	}
	return $this->redirectToRoute('admin_user_index');
    }

    /**
     * @Route("users/{id}", name="admin_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EntityUser $entityUser): Response
    {
	if(!$this->isGranted('POST_EDIT',$entityUser)){

		if ($this->isCsrfTokenValid('delete'.$entityUser->getId(), $request->request->get('_token'))) {
		    $entityManager = $this->getDoctrine()->getManager();
		    $entityManager->remove($entityUser);
		    $entityManager->flush();
		}
	}

        return $this->redirectToRoute('admin_user_index');
    }
}
