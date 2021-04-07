<?php

namespace App\Controller;

use App\Entity\EntityPeople;
use App\Entity\EntityUser;
use App\Entity\EntityUserPermissions;
use App\Form\EntityUserType;
use App\Repository\EntityRolesRepository;
use App\Repository\EntityUserRepository;
use App\Security\Voter\PermissionCalculator;
use App\Utils\MongoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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
        $user = $this->get('security.token_storage')->getToken()->getUser();

        //filtres à appliquer ici
        $list = PermissionCalculator::checkList($user,"users",$entityUserRepository->findAll());
        $edit = PermissionCalculator::checkEdit($user,"users",$list);
        return $this->render('entity_user/index.html.twig', [
            'entity_users' => $list,
            'edits' => $edit
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
     * @Route("users/peopleAndUser", name="admin_user_people_new", methods={"GET","POST"})
     */
	public function newUserPeople(Request $request,EntityRolesRepository $entityRolesRepository): Response{
		
		//creation d'un nouvelle utilisateur 
        $entityUser = new EntityUser();
		//creation d'une nouvelle personnre
		$entityPeople = new EntityPeople();

		if($this->isGranted('POST_EDIT',$entityUser)){

			// Ajout des champs supplémentaire dans le formulraire pour la saisie de la "Personne"
			$form = $this->createForm(EntityUserType::class, $entityUser)
				->add('birthDate', BirthdayType::class, ['mapped' => false])
				->add('postal_code', TextType::class, ['mapped' => false])
				->add('city', TextType::class, ['mapped' => false])
				->add('newsLetter', CheckboxType::class, ['mapped' => false, 'required' => false]);
			$form->handleRequest($request);


			if ($form->isSubmitted() && $form->isValid()) {
				$entityManager = $this->getDoctrine()->getManager();
				/**
				* Hashage du mot de passe avec le protocole BCRYPT juste avant l'enregistrement en bd.
				*/
				$entityUser->bCryptPassword($entityUser->getPassword());


				//parcours des entityRoles du formulaire pour leur attribuer le user actuellement créer 
				foreach($form->getdata()->getEntityRoles() as $Role){
					$Role->addUser($entityUser);
				}

				// mapping des info user dans people
				$mongoman = new MongoManager();
				$entityPeople->setFirstname($entityUser->getFirstName());
				$entityPeople->setName($entityUser->getLastName());
				$entityPeople->setAdresseMailing($entityUser->getEmail());
				$entityPeople->setInstitution($entityUser->getInstitution());
				$entityPeople->setBirthdate($form['birthDate']->getData());
				$entityPeople->setPostalCode($form['postal_code']->getData());
				$entityPeople->setCity($form['city']->getData());
				$entityPeople->setNewsletter($form['newsLetter']->getData());
				$entityPeople->setAddDate(new \DateTime("now"));
				$entityPeople->setSheetId($sheetId=$mongoman->insertSingle("Entity_person_sheet",[]));

				//mise a jour de la base de données
				$entityManager->persist($entityUser);
				$entityManager->persist($entityPeople);
				$entityManager->flush();
				return $this->redirectToRoute('admin_user_index');

			}

			return $this->render('entity_user/new.html.twig', [ 'entity_user' => $entityUser, 'entity_roles' => $entityRolesRepository->findAll(), 'form' => $form->createView()]);

		} else {
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
	if($this->isGranted('POST_EDIT',$entityUser)){
		//creation d'un nouveau formulaire de type User
        $mongoman = new MongoManager();
		$form = $this->createForm(EntityUserType::class, $entityUser, ["extra_fields_message" => 'edit'])
		->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
		    $entityManager = $this->getDoctrine()->getManager();
            //parcours tous les roles de l'utilisateurs pour les supprimez

            foreach($entityUser->getEntityUserPermissions() as $perm){
                $entityUser->removeEntityPerm($perm);
            }
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
