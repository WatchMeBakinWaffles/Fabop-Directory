<?php

namespace App\Controller;

use App\Entity\EntityInstitutions;
use App\Entity\EntityModele;
use App\Entity\EntityPeople;
use App\Entity\EntityPerformances;
use App\Entity\EntityRoles;
use App\Entity\EntityShows;
use App\Entity\EntityTags;
use App\Entity\EntityUser;
use App\Form\EntityUserType;
use App\Form\PermissionForm;
use App\Repository\EntityRolesRepository;
use App\Repository\PermissionsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Utils\MongoManager;

class PermissionController extends AbstractController
{
    /**
     * @Route("admin/permission", name="permission")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
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
       return $this->render('permission/index.html.twig', [
            'form' => $form->createView()
        ]);
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

        return $this->render('permission/permission_create.html.twig', ['data' => $data]);

    }


}

