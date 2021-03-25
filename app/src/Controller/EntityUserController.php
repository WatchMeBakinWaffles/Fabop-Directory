<?php

namespace App\Controller;

use App\Entity\EntityRoles;
use App\Entity\EntityUserPermissions;
use App\Form\EntityRolesType;
use App\Repository\EntityRolesRepository;
use App\Repository\PermissionsRepository;
use App\Entity\EntityUser;
use App\Form\EntityUserType;
use App\Repository\EntityUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
        return $this->render('entity_roles/index.html.twig', [
			'entity_roles' => $entityRolesRepository->findAll(),
        ]);
    }
    /**
     * @Route("users/new", name="admin_user_new", methods={"GET","POST"})
     */
    public function new(Request $request,EntityRolesRepository $entityRolesRepository): Response
    {
		//creation d'un nouvelle utilisateur 
        $entityUser = new EntityUser();

	if($this->isGranted('POST_EDIT',$entityUser)){
		//création d'un formulaire de type User
		$form = $this->createForm(EntityUserType::class, $entityUser);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
		    $entityManager = $this->getDoctrine()->getManager();
		    //$entityManager->persist($entityUser);
		    /**
		    * Hashage du mot de passe avec le protocole BCRYPT juste avant l'enregistrement en bd.
		    */
			$entityUser->bCryptPassword($entityUser->getPassword());


			//parcours des entityRoles du formulaire pour leur attribuer le user actuellement créer 
			foreach($form->getdata()->getEntityRoles() as $Role){
				$Role->addUser($entityUser);
			}

			//mise a jour de la base de données
		    $entityManager->persist($entityUser);
			$entityManager->flush();
		    return $this->redirectToRoute('admin_user_index');
		}

		return $this->render('entity_user/new.html.twig', [
			'entity_user' => $entityUser,
			'entity_roles' => $entityRolesRepository->findAll(),
		    'form' => $form->createView(),
		]);
	}
	else{
		return $this->render('error403forbidden.html.twig');
	}
	return $this->redirectToRoute('admin_user_index');
    }

    /**
     * @Route("users/{id}", name="admin_user_show", methods={"GET"})
     */
	public function show(EntityUser $entityUser): Response
    {
	if($this->isGranted('POST_VIEW',$entityUser)){

		return $this->render('entity_user/show.html.twig', [
		    'entity_user' => $entityUser,
		]);
	}
	else{
		return $this->render('error403forbidden.html.twig');
	}
	return $this->redirectToRoute('admin_user_index');
    }

    /**
     * @Route("users/{id}/edit", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EntityUser $entityUser): Response
    {		
		//parcours tous les roles de l'utilisateurs pour les supprimez	
		foreach($entityUser->getEntityRoles() as $RoleARetirer){
		$entityUser->removeEntityRole($RoleARetirer);
	}
	if($this->isGranted('POST_EDIT',$entityUser)){
		//creation d'un nouveau formulaire de type User
        $mongoman = new MongoManager();
		$form = $this->createForm(EntityUserType::class, $entityUser)
		->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
		    $entityManager = $this->getDoctrine()->getManager();
		    /**
		    * Hashage du mot de passe avec le protocole BCRYPT juste avant l'enregistrement en bd.
			*/
		    if (password_get_info($entityUser->getPassword())['algoName'] === 'unknown') {
                $entityUser->bCryptPassword($entityUser->getPassword());
            }
			//parcours des entityRoles du formulaire pour leur attribuer le user actuellement editer
			foreach($form->getdata()->getEntityRoles() as $Role){
			 $Role->addUser($entityUser);
			}
            foreach ($form->get("permissions")->getData() as $perm) {
                $entityUserPerm = new EntityUserPermissions();
                $entityUserPerm->setSheetId($perm['_id']);
                $entityUser->setEntityUserPermissions($entityUserPerm);
            }
			//mise a jour de la base de données
		    $entityManager->persist($entityUser);
			$entityManager->flush();

		    return $this->redirectToRoute('admin_user_index');
		}

		return $this->render('entity_user/edit.html.twig', [
			'entity_user' => $entityUser,
		    'form' => $form->createView(),
		]);
	}
	else{
		return $this->render('error403forbidden.html.twig');
	}
     return $this->redirectToRoute('admin_user_index');
    }

    /**
     * @Route("users/{id}", name="admin_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EntityUser $entityUser): Response
    {
	if($this->isGranted('POST_EDIT',$entityUser)){

		if ($this->isCsrfTokenValid('delete'.$entityUser->getId(), $request->request->get('_token'))) {
		    $entityManager = $this->getDoctrine()->getManager();
		    $entityManager->remove($entityUser);
		    $entityManager->flush();
		}
	}
	else{
		return $this->render('error403forbidden.html.twig');
	}

        return $this->redirectToRoute('admin_user_index');
    }
}
