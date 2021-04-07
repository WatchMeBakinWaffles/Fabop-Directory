<?php

namespace App\Controller;

use App\Entity\EntityUser;
use App\Entity\EntityRoles;
use App\Entity\Permissions;
use App\Security\Voter\PermissionCalculator;
use App\Utils\MongoManager;
 
use App\Form\EntityUserType;
use App\Form\EntityRolesType;

use App\Repository\EntityUserRepository;
use App\Repository\PermissionsRepository;
use App\Repository\EntityRolesRepository;

use Doctrine\DBAL\Types\TextType;
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
       $user = $this->get('security.token_storage')->getToken()->getUser();

       //filtres à appliquer ici
       $list = PermissionCalculator::checkList($user,"institutions",$entityRolesRepository->findAll());
       $edit = PermissionCalculator::checkEdit($user,"institutions",$list);
       return $this->render('entity_roles/index.html.twig', [
        'entity_roles' => $list,
        'edits' => $edit
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
        $choiceRights = array(
            'Oui' => 'oui',
            'Non' => 'non',
            'Inchangés' => 'inchanges',
        );

        $choiceTraduction = array(
            1 => 'oui',
            -1 => 'non',
            0 => 'inchanges'
        );
        $choiceTraductionReverse = array_flip($choiceTraduction);
        $entityRoles = new EntityRoles();
        $entityList = array('show', 'tags', 'peoples', 'users', 'models', 'institutions', 'roles', 'restaurations');

        //droits sous forme booleen
        $choicesBool = array(
            'Oui'=> True,
            'Non'=>False
        );
        if(!$this->isGranted('POST_EDIT',$entityRoles)) {
            $defaultData = ['message' => 'Type your message here'];
            $form = $this->createFormBuilder($defaultData)
                ->add('nom', null, array('required' => true))
                ->add('import', ChoiceType::class, [ 'choices' =>$choicesBool])
                ->add('export', ChoiceType::class, [ 'choices' =>$choicesBool])
                ->add('connection', ChoiceType::class, [ 'choices' =>$choicesBool])
                ->getForm();
            $count = 0;
            foreach ($entityList as $perm) {
                $form
                    ->add($perm, null, [
                            'data' => $perm,
                            'disabled' => true,
                            'label' => ' '
                        ]
                    )
                    ->add('droits_lecture' . $perm, ChoiceType::class, [
                        'choices' => $choiceRights,
                        'expanded' => false,
                        'label' => 'Droit de lecture'
                    ])
                    ->add('droits_ecriture' . $perm, ChoiceType::class, [
                        'choices' => $choiceRights,
                        'expanded' => false,
                        'label' => "Droit d'écriture"
                    ]);
            }

            $mongoman = new MongoManager();

            //l'ajout
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $data = $form->getData();
                $RoleInForm = new EntityRoles();
                $RoleInForm->setNom($data['nom']);
                $json = [];
                $json["label"] = $data["nom"];
                $json["import"] = $data["import"];
                $json["export"] = $data["export"];
                $json["connection"] = $data["connection"];
                $c = 0;
                foreach ($entityList as $entity) {
                    $c++;
                    $json["permissions"][$c]["entityType"] = $entity;
                    $json["permissions"][$c]["rights"][0]["filters"][0]["field"] = '*';
                    $json["permissions"][$c]["rights"][0]["filters"][0]["value"] = '*';
                    $json["permissions"][$c]["rights"][0]["read"] = $choiceTraductionReverse[$data['droits_lecture' . $entity]];
                    $json["permissions"][$c]["rights"][0]["write"] = $choiceTraductionReverse[$data["droits_ecriture" . $entity]];
                }

                $sheetPermission = $mongoman->insertSingle("permissions_user", $json);
                $PermissionsInForm = new Permissions();
                $PermissionsInForm->setSheetId($sheetPermission);
                $RoleInForm->setPermissions($PermissionsInForm);
                $RoleInForm->setEditable(False);

                $entityManager->persist($PermissionsInForm);
                $entityManager->flush();
                if (null != $request->request->get('roles_data')) {
                    $sheetId = $mongoman->insertSingle("permissions_user", $request->request->get('roles_data'));
                } else {
                    $sheetId = $mongoman->insertSingle("permissions_user", []);
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
        $mongoman = new MongoManager();
        $data_permissions = $mongoman->getDocById("permissions_user",$entityRoles->getPermissions()->getSheetId());

        $choiceRights = array(
            'Oui' => 'oui',
            'Non' => 'non',
            'Inchangés' => 'inchanges',
        );

        $choiceTraduction = array(
            1 => 'oui',
            -1 => 'non',
            0 => 'inchanges'
        );
        $choiceTraductionReverse = array_flip($choiceTraduction);

        $entityList = array('show', 'tags', 'peoples', 'users', 'models', 'institutions', 'roles', 'restaurations');
            
        //droits sous forme booleen
        $choicesBool = array(
            'Oui'=> True,
            'Non'=>False
        );
        if(!$this->isGranted('POST_EDIT',$entityRoles)){
            $defaultData = ['message' => 'Type your message here'];
            $form = $this->createFormBuilder($defaultData)
                ->add('nom', null,array('required' => true, 'data' => $data_permissions['label']))
                ->add('import', ChoiceType::class, [ 'choices' =>$choicesBool, 'data' => $data_permissions['import']])
                ->add('export', ChoiceType::class, [ 'choices' =>$choicesBool, 'data' => $data_permissions['export']])
                ->add('connection', ChoiceType::class, [ 'choices' =>$choicesBool, 'data' => $data_permissions['connection']])
                ->getForm();
            $count = 0;
            foreach ($data_permissions['permissions'] as $perm) {
                $form
                    ->add($perm['entityType'], null, [
                    'data' => $perm['entityType'],
                    'disabled' => true,
                    'label' => ' '
                    ]
                   )
                    ->add('droits_lecture'.$perm['entityType'], ChoiceType::class, [
                        'choices' => $choiceRights,
                        'expanded' => false,
                        'label' => 'Droit de lecture',
                        'data' => $choiceTraduction[$perm['rights'][0]['read']]
                    ])
                    ->add('droits_ecriture'.$perm['entityType'], ChoiceType::class, [
                        'choices' => $choiceRights,
                        'expanded' => false,
                        'label' => "Droit d'écriture",
                        'data' => $choiceTraduction[$perm['rights'][0]['write']]
                    ]);
            }

            $form->handleRequest($request);
    		$mongoman = new MongoManager();

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $data = $form->getData();
                $permissions = $entityRoles->getPermissions();
                $entityManager->persist($entityRoles);
                $entityManager->flush();
                //push
                $json = [];
                $json["label"] = $data["nom"];
                $json["role"] = true;
                $json["import"] = $data["import"];
                $json["export"] = $data["export"];
                $json["connection"] = $data["connection"];
                $c = 0;
                foreach ($entityList as $entity) {
                    $c++;
                    $json["permissions"][$c]["entityType"] = $entity;
                    $json["permissions"][$c]["rights"][0]["filters"][0]["field"] = '*';
                    $json["permissions"][$c]["rights"][0]["filters"][0]["value"] = '*';
                    $json["permissions"][$c]["rights"][0]["read"] = $choiceTraductionReverse[$data['droits_lecture' . $entity]];
                    $json["permissions"][$c]["rights"][0]["write"] = $choiceTraductionReverse[$data["droits_ecriture" . $entity]];
                }
                $mongoman->updateSingleValueByJson("permissions_user",$permissions->getSheetId(), $json);
                $permissions->setSheetId($permissions->getSheetId());
                $entityRoles->setPermissions($permissions);
                $entityRoles->setNom($data["nom"]);
                $entityManager->persist($permissions);
                $entityManager->flush();

               return $this->redirectToRoute('admin_roles_index', ['id' => $entityRoles->getId()]);
            }
        return $this->render('entity_roles/edit.html.twig', [
            'entity_roles' => $entityRoles,
            'form' => $form->createView(),
        ]);
   }
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
