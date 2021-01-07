<?php

namespace App\Controller;

use App\Entity\EntityModele;
use App\Entity\EntityPeople;
use App\Entity\EntityUser;
use App\Form\EntityModeleType;
use App\Form\EntityPeopleType;
use App\Repository\EntityModeleRepository;
use App\Utils\MongoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/manager/modeles")
 */
class EntityModeleController extends AbstractController
{

    /**
     * @Route("/", name="entity_modele_index", methods="GET")
     * @param EntityModeleRepository $entityModeleRepository
     * @return Response
     */
    public function index(EntityModeleRepository $entityModeleRepository): Response
    {
        //filtres à appliquer ici
        return $this->render('entity_modeles/index.html.twig', [
            'entity_modeles' => $entityModeleRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="entity_modeles_new", methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $em_modele = $this->getDoctrine()->getManager();

        // Récupère l'adresse mail du créateur du modèle
        $userName = $this->getUser()->getUsername();
        $user = $this->getDoctrine()
            ->getRepository(EntityUser::class)
            ->findOneByEmail($userName);

        //Récupère les métadatas de l'entité Institutions
//        $em_people = $this->getDoctrine()->getManager();
//        $metadata = $em_people->getClassMetadata(EntityPeople::class);
        $entity_people = new EntityPeople();
        $form_people = $this->createForm(EntityPeopleType::class, $entity_people);
        $form_people->handleRequest($request);
    
        $entity_modele = new EntityModele();
        $entity_modele->addUser($user);
        $form = $this->createForm(EntityModeleType::class, $entity_modele);
        $form->handleRequest($request);

        $mongoman = new MongoManager();

        if($form->isSubmitted() && $form->isValid()) {

            if($request->request->get("custom_data") !== null){
                foreach($request->request->get("custom_data") as $elem){
                    $data[$elem['label']] = $elem['value'];
                }
            }

            // Mise en bdd Mongo de la fiche doc --> return IdMongo
            if (isset($data)){
                $sheetId=$mongoman->insertSingle("Entity_Custom_sheet",$data);
            }
            else{
                $sheetId=$mongoman->insertSingle("Entity_Custom_sheet",[]);
            }

            // Mise en bdd MySQL de l'ID de fiche de données
            $entity_modele->setSheetId($sheetId);

            $em_modele->persist($entity_modele);
            $em_modele->flush();
            return $this->redirectToRoute('entity_modele_index');
        }

        return $this->render('entity_modeles/new.html.twig', [
            "form" => $form->createView(),
            "form_people" => $form_people->createView(),
            "entity_modeles" => $entity_modele,
        ]);
    }

    /**
     * @Route("/{id}", name="entity_modeles_show", methods="GET")
     * @param EntityModele $entityModele
     * @return Response
     */
    public function show(EntityModele $entityModele): Response
    {
        return $this->render('entity_modeles/show.html.twig', ['entityModele' => $entityModele]);
    }



}