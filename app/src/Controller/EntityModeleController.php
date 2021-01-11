<?php

namespace App\Controller;

use App\Entity\EntityModele;
use App\Entity\EntityPeople;
use App\Entity\EntityUser;
use App\Exception\DocumentNotFoundException;
use App\Form\EntityModeleType;
use App\Form\EntityPeopleType;
use App\Repository\EntityModeleRepository;
use App\Utils\MongoManager;
use App\Utils\XLSXWriter;
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
     * @Route("/", name="entity_modeles_index", methods="GET")
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

        $entity_people = new EntityPeople();
        $form_people = $this->createForm(EntityPeopleType::class, $entity_people);
        $form_people->handleRequest($request);
    
        $entity_modele = new EntityModele();
        $entity_modele->addUser($user);
        $form = $this->createForm(EntityModeleType::class, $entity_modele);
        $form->handleRequest($request);

        $writer = new XLSXWriter();

        $mongoman = new MongoManager();

        if($form->isSubmitted() && $form->isValid()) {

            $value = [];
            if($request->request->get("custom_data") !== null){
                foreach($request->request->get("custom_data") as $elem){
                    $data[$elem['label']] = $elem['value'];
                    $value[] = $elem['value'];
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

            //Appel la méthode writeCustomModele de XLSXWriter()
            $writer->writeCustomModele($entity_modele->getName(),$entity_modele->getSheetId(),$value);

            $em_modele->persist($entity_modele);
            $em_modele->flush();
            return $this->redirectToRoute('entity_modeles_index');
        }

        return $this->render('entity_modeles/new.html.twig', [
            "form" => $form->createView(),
            "form_people" => $form_people->createView(),
            "entity_modeles" => $entity_modele,
        ]);
    }

    /**
     * @Route("/{id}", name="entity_modeles_show", methods="GET")
     * @param EntityModele $entity_modele
     * @return Response
     * @throws DocumentNotFoundException
     */
    public function show(EntityModele $entity_modele): Response
    {
        $mongoman = new MongoManager();
        return $this->render('entity_modeles/show.html.twig', [
            'entity_modeles' => $entity_modele,
            'entity_modeles_data' => $mongoman->getDocById("Entity_Custom_sheet",$entity_modele->getSheetId()),
        ]);
    }



    /**
     * @Route("/{id}", name="entity_modeles_delete", methods="DELETE")
     */
    public function delete(Request $request, EntityModele $entity_modele): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entity_modele->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $mongoman = new MongoManager();
            $mongoman->deleteSingleById("Entity_Custom_sheet",$entity_modele->getSheetId());

            $em->remove($entity_modele);
            $em->flush();
        }

        return $this->redirectToRoute('entity_modeles_index');
    }

    /**
     * @Route("/{id}/edit", name="entity_modeles_edit", methods="GET|POST")
     */
    public function edit(Request $request, EntityModele $entity_modele): Response
    {
        $form = $this->createForm(EntityModeleType::class, $entity_modele);
        $form->handleRequest($request);
        $mongoman = new MongoManager();
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            if (null != $request->request->get('modele_data')){
                $dataId=$entity_modele->getSheetId();
                foreach( $request->request->get('modele_data') as $key=>$value){
                    if ($value!=''){
                        $mongoman->updateSingleValueById("Entity_Custom_sheet",$dataId,$key,$value);
                    }else{
                        $mongoman->unsetSingleValueById("Entity_Custom_sheet",$dataId,$key);
                    }
                }
            }
            $em->flush();

            return $this->redirectToRoute('entity_modeles_index', ['id' => $entity_modele->getId()]);
        }

        return $this->render('entity_modeles/edit.html.twig', [
            'entity_modeles' => $entity_modele,
            'form' => $form->createView(),
            'entity_Custom_data' => $mongoman->getDocById("Entity_Custom_sheet",$entity_modele->getSheetId()),
        ]);
    }

}