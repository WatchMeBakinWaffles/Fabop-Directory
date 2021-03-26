<?php

namespace App\Controller;

use App\Entity\EntityUser;
use App\Entity\EntityRoles;
use App\Entity\Permissions;
use App\Utils\MongoManager;
 
use App\Form\EntityUserType;
use App\Form\EntityRolesType;

use App\Repository\EntityUserRepository;
use App\Repository\PermissionsRepository;
use App\Repository\EntityRolesRepository;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
   public function index(EntityRolesRepository $entityRolesRepository,PermissionsRepository $permissionsRepository): Response
   {
       return $this->render('entity_roles/index.html.twig', [
        'entity_roles' => $entityRolesRepository->findAll(),
       ]);
   }
    /**
     * @Route("/", name="admin_user_index", methods={"GET"})
     */
    public function index_to_users_list(EntityRolesRepository $entityRolesRepository,EntityUserRepository $entityUserRepository): Response
    {
        return $this->render('entity_user/index.html.twig', [
            'entity_users' => $entityUserRepository->findAll(),
            'entity_roles' => $entityRolesRepository->findAll(),

        ]);
    }

    /**
     * @Route("roles/new", name="admin_roles_new", methods={"GET","POST"})
     */
    public function new(Request $request,EntityUserRepository $entityUserRepository): Response
    {  

        //droits à attribuer
        $choicesPerm = array(
        'rien' => ' ',
        'R' => 'R',
        'W'=> 'W',
        'RW' => 'RW'
    );

    //droits sous forme booleen
    $choicesBool = array(
        'True'=> True,
        'False'=>False
    );
        //test des droits 
        $entityRoles = new EntityRoles();
        if(!$this->isGranted('POST_EDIT',$entityRoles)){

        //formulaire de permissions constituée de droits 
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
        ->add('nom', null,array('required' => true))
        ->add('User_listing', EntityType::class, array('class' =>EntityUser::class, 'choice_label'=>'email','multiple'=>true, 'expanded'=>true))
        ->add('shows',ChoiceType::class, [
            'choices' => $choicesPerm])
        ->add('tags',ChoiceType::class, [
            'choices' => $choicesPerm])
        ->add('shows',ChoiceType::class, [
            'choices' => $choicesPerm])
        ->add('peoples',ChoiceType::class, [
            'choices' => $choicesPerm])
        ->add('users',ChoiceType::class, [
            'choices' => $choicesPerm])
        ->add('models',ChoiceType::class, [
            'choices' => $choicesPerm])
        ->add('institutions',ChoiceType::class, [
            'choices' => $choicesPerm])
        ->add('roles',ChoiceType::class, [
            'choices' => $choicesPerm])
        ->add('import',ChoiceType::class, [
            'choices' => $choicesBool])
        ->add('export',ChoiceType::class, [
            'choices' => $choicesBool])
        ->add('connection',ChoiceType::class, [
            'choices' => $choicesBool])
        ->add('restaurations',ChoiceType::class, [
            'choices' => $choicesPerm])
        ->getForm();
    
        $form->handleRequest($request);

        $mongoman = new MongoManager();

        //l'ajout

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $RoleInForm = new EntityRoles();
            $RoleInForm->setNom($data['nom']);
            $sheetPermission=$mongoman->insertSingle("permissions_user",[
                'shows'=> $data["shows"],
                'tags'=>$data["tags"],
                'peoples'=>$data["peoples"],
                'users'=>$data["users"],
                'models'=>$data["models"],
                'institutions'=>$data["institutions"],
                'roles'=>$data["roles"],
                'import'=>$data["import"],
                'export'=>$data["export"],
                'connection'=>$data["connection"],
                'restaurations'=>$data["restaurations"]
            ]);
            $PermissionsInForm = new Permissions();
            $PermissionsInForm->setSheetId($sheetPermission);
            $RoleInForm->setPermissions($PermissionsInForm);
            $RoleInForm->setEditable(False);

            $entityManager->persist($PermissionsInForm);
            $entityManager->flush();
            foreach($data['User_listing'] as $userEmail){
                if($userEmail != null){
                $userem = strval($userEmail);
                $user = $entityUserRepository->findOneByemail($userem);
                    $user->addEntityRole($RoleInForm);
                    //$entityManager->persist($user);
                    $entityManager->flush();
                }
            }
            if (null != $request->request->get('roles_data')){
		        $sheetId=$mongoman->insertSingle("permission_users",$request->request->get('roles_data'));
		    }else{
		        $sheetId=$mongoman->insertSingle("permission_users",[]);
		    }

		    // Mise en bdd MySQL de l'ID de fiche de données

            return $this->redirectToRoute('admin_roles_index');
        }

        return $this->render('entity_roles/new.html.twig', [
            'entity_roles' => $entityRoles,
            'form' => $form->createView(),
        ]);
    }
    return $this->redirectToRoute('admin_roles_index');
    }


    /**
     * @Route("roles/{id}", name="admin_roles_show", methods={"GET"})
     */
    public function show(EntityRoles $entityRoles): Response
    {
        $permissions = $entityRoles->getPermissions();
	    $mongoman = new MongoManager();
		$data_permissions = $mongoman->getDocById("permissions_user",$permissions->getSheetId());
        return $this->render('entity_roles/show.html.twig', [
            'entity_roles' => $entityRoles,
		    'permissions' => $data_permissions,
        ]);
    }

    /**
     * @Route("roles/{id}/edit", name="admin_roles_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EntityRoles $entityRoles): Response
    {
                //droits à attribuer
                $choicesPerm = array(
                    'rien' => ' ',
                    'R' => 'R',
                    'W'=> 'W',
                    'RW' => 'RW'
                );
            
                //droits sous forme booleen
                $choicesBool = array(
                    'True'=> True,
                    'False'=>False
                );
        //if(!$this->isGranted('POST_EDIT',$entityRoles)){
            $defaultData = ['message' => 'Type your message here'];
            $form = $this->createFormBuilder($defaultData)
            ->add('nom', null,array('required' => true))
            ->add('shows',ChoiceType::class, [
                'choices' => $choicesPerm])
            ->add('tags',ChoiceType::class, [
                'choices' => $choicesPerm])
            ->add('shows',ChoiceType::class, [
                'choices' => $choicesPerm])
            ->add('peoples',ChoiceType::class, [
                'choices' => $choicesPerm])
            ->add('users',ChoiceType::class, [
                'choices' => $choicesPerm])
            ->add('models',ChoiceType::class, [
                'choices' => $choicesPerm])
            ->add('institutions',ChoiceType::class, [
                'choices' => $choicesPerm])
            ->add('roles',ChoiceType::class, [
                'choices' => $choicesPerm])
            ->add('import',ChoiceType::class, [
                'choices' => $choicesBool])
            ->add('export',ChoiceType::class, [
                'choices' => $choicesBool])
            ->add('connection',ChoiceType::class, [
                'choices' => $choicesBool])
            ->add('restaurations',ChoiceType::class, [
                'choices' => $choicesPerm])
            ->getForm();
        
            $form->handleRequest($request);
    		$mongoman = new MongoManager();

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $data = $form->getData();
                $permissions = $entityRoles->getPermissions();


                $entityRoles->setNom($data['nom']);
                $entityManager->persist($entityRoles);
                //$data_permissions = $mongoman->getDocById("permissions_user",$permissions->getSheetId());
                $entityManager->flush();

                $sheetPermission=$mongoman->insertSingle("permissions_user",[
                    'shows'=> $data["shows"],
                    'tags'=>$data["tags"],
                    'peoples'=>$data["peoples"],
                    'users'=>$data["users"],
                    'models'=>$data["models"],
                    'institutions'=>$data["institutions"],
                    'roles'=>$data["roles"],
                    'import'=>$data["import"],
                    'export'=>$data["export"],
                    'connection'=>$data["connection"],
                    'restaurations'=>$data["restaurations"]
                ]);
                $permissions->setSheetId($sheetPermission);
                $entityRoles->setPermissions($permissions);
                $entityManager->persist($permissions);
                $entityManager->flush();

                return $this->redirectToRoute('admin_roles_index', ['id' => $entityRoles->getId()]);
            }

          //  return $this->redirectToRoute('admin_roles_index');
        return $this->render('entity_roles/edit.html.twig', [
            'entity_roles' => $entityRoles,
            'form' => $form->createView(),
        ]);
   // }
    return $this->redirectToRoute('admin_roles_index');
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
