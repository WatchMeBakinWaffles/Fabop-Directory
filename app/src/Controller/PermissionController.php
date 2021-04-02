<?php

namespace App\Controller;

use App\Entity\EntityInstitutions;
use App\Entity\EntityModele;
use App\Entity\EntityPeople;
use App\Entity\EntityRoles;
use App\Entity\EntityShows;
use App\Entity\EntityTags;
use App\Entity\EntityUser;
use App\Entity\EntityUserPermissions;
use App\Form\EntityUserType;
use App\Form\PermissionForm;
use App\Form\PermissionFormEdit;
use App\Utils\MongoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PermissionController extends AbstractController
{

    /**
     * @Route("admin/permission", name="permission")
     */
    public function index(): Response
    {
        $mongoman = new MongoManager();
        return $this->render('permission/index.html.twig', [
            'all_perms' => $mongoman->getAllPermission("permissions_user"),
        ]);
    }

    /**
     * @Route("admin/permission/create", name="permission_new")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $form = $this->createForm(PermissionForm::class)
            ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $count = 0;
            if($request->request->get("custom_data") !== null){
                foreach($request->request->get("custom_data") as $elem){
                    $count++;
                    foreach($elem as $key => $val){
                        $data[$key . $count] = $val;
                    }}
            }
            $data['nombre_de_filtres'] = $count;
            if (isset($data['ajouter_un_filtre']) && $data['ajouter_un_filtre'] === 'non' || isset($data['valeur_du_filtre0']) && $data['valeur_du_filtre0'] !== null) {
                return $this->redirectToRoute('permission_create', array(
                    'data' => $data
                ));
            }
        }
        return $this->render('permission/create_perm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/permission/{id}/edit", name="admin_permission_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, $id): Response
    {
            //creation d'un nouveau formulaire de type User
            $mongoman = new MongoManager();
            $perm = $mongoman->getDocById("permissions_user",$id);
            $form = $this->createForm(PermissionFormEdit::class, $perm)
                ->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $count = 1;

                if($request->request->get("custom_data") !== null){
                    foreach($request->request->get("custom_data") as $elem){
                        $count++;
                        foreach($elem as $key => $val){
                            $data[$key . $count] = $val;
                        }}
                }
                $data['nombre_de_filtres'] = $count + $data["count_perm"] - 1;
             return $this->redirectToRoute('permission_update', array(
                        'data' => $data
                    ));
            }
            return $this->render('permission/edit.html.twig', [
                'form' => $form->createView(),
                'perm' => $perm,
            ]);
    }

    /**
     * @Route("admin/permission/{id}/delete", name="permission_delete")
     */
    public function delete($id): Response
    {
        $mongoman = new MongoManager();
        $mongoman->deleteSingleById("permissions_user",$id);
        $this->addFlash('success', 'La permission a bien été supprimé');
        return $this->redirectToRoute('permission');
    }



    /**
     * @Route("admin/permission_create", name="permission_create")
     * @param Request $request
     * @return Response
     */
    public function permission_create(Request $request): Response
    {
        $data = $request->get('data');
        $classTraduction = array(
            EntityShows::class => "show",
            EntityTags::class => "tags",
            EntityPeople::class => "peoples",
            EntityUser::class => "users",
            EntityModele::class => "models",
            EntityInstitutions::class => "institutions",
            EntityRoles::class => "roles"
        );
        $choiceTraduction = array(
            'oui'=> 1,
            'non' => -1,
            'inchanges' => 0
        );

        //push
        $json = [];
        $json["label"] = $data["nom_de_la_permission"];
        $json["permissions"]["entityType"] = $classTraduction[$data["entite"]];
        if ( $data["ajouter_un_filtre"] == 'oui') {
            for ($i=0; $i<=$data["nombre_de_filtres"]; $i++) {
                $json["permissions"][0]["rights"][$i]["filters"][0]["field"] = $data["champ_a_filtrer" . ($i)];
                $json["permissions"][0]["rights"][$i]["filters"][0]["value"] = $data["valeur_du_filtre" . ($i)];
                $json["permissions"][0]["rights"][$i]["read"] = $choiceTraduction[$data["droits_lecture" . ($i)]];
                $json["permissions"][0]["rights"][$i]["write"] = $choiceTraduction[$data["droits_ecriture" . ($i)]];
            }
        } else {
            $json["permissions"][0]["rights"][0]["filters"][0]["field"] = "*";
            $json["permissions"][0]["rights"][0]["filters"][0]["value"] = "*";
            $json["permissions"][0]["rights"][0]["read"] = 1;
            $json["permissions"][0]["rights"][0]["write"] = 1;
        }


        $mongoman = new MongoManager();
        $mongoman->insertSingle("permissions_user",$json);
        // Retrieve flashbag from the controller
        $this->addFlash('success', 'La permission a bien été créé');
        $mongoman = new MongoManager();
        return $this->redirectToRoute('permission');

    }

    /**
     * @Route("admin/permission_update", name="permission_update")
     * @param Request $request
     * @return Response
     */
    public function permission_update(Request $request): Response
    {
        $mongoman = new MongoManager();

        $data = $request->get('data');
        $id = '';
        foreach ($data['_id'] as $d) {
           $id = $d;
        }
       $mongoman->deleteSingleById("permissions_user",$id);
        $choiceTraduction = array(
            'oui'=> 1,
            'non' => -1,
            'inchanges' => 0
        );

        //push
        $json = [];
        $json["label"] = $data["nom_de_la_permission"];
        $json["permissions"]["entityType"] = $data["permissions"]["entityType"];
        if ( $data["nombre_de_filtres"] > 0) {
            for ($i=0; $i<$data["nombre_de_filtres"]; $i++) {
                $json["permissions"][0]["rights"][$i]["filters"][0]["field"] = $data["champ_a_filtrer" . ($i+1)];
                $json["permissions"][0]["rights"][$i]["filters"][0]["value"] = $data["valeur_du_filtre" . ($i+1)];
                $json["permissions"][0]["rights"][$i]["read"] = $choiceTraduction[$data["droits_lecture" . ($i+1)]];
                $json["permissions"][0]["rights"][$i]["write"] = $choiceTraduction[$data["droits_ecriture" . ($i+1)]];
            }
        } else {
            $json["permissions"][0]["rights"][0]["filters"][0]["field"] = "*";
            $json["permissions"][0]["rights"][0]["filters"][0]["value"] = "*";
            $json["permissions"][0]["rights"][0]["read"] = 1;
            $json["permissions"][0]["rights"][0]["write"] = 1;
        }


        $mongoman = new MongoManager();
        $mongoman->insertSingle("permissions_user",$json);
        // Retrieve flashbag from the controller
        $this->addFlash('success', 'La permission a bien été modifié');
        $mongoman = new MongoManager();
        return $this->redirectToRoute('permission');

    }



}