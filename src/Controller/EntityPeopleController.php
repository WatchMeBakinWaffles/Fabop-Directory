<?php

namespace App\Controller;

use App\Entity\EntityPeople;
use App\Form\EntityPeopleType;
use App\Repository\EntityPeopleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\MongoManager;

/**
 * @Route("/manager/people")
 */
class EntityPeopleController extends AbstractController
{
    /**
     * @Route("/", name="manager/entity_people_index", methods="GET")
     */
    public function index(EntityPeopleRepository $entityPeopleRepository): Response
    {
        //filtres à appliquer ici
        return $this->render('entity_people/index.html.twig', ['entity_people' => $entityPeopleRepository->findAll()]);
    }

    /**
     * @Route("/new", name="manager/entity_people_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $entityPerson = new EntityPeople();
        $form = $this->createForm(EntityPeopleType::class, $entityPerson);
        $form->handleRequest($request);
        $mongoman = new MongoManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Mise en bdd Mongo de l fiche doc --> return IdMongo
            if (null != $request->request->get('person_data')){
                $sheetId=$mongoman->insertSingle("Entity_person_sheet",$request->request->get('person_data'));
            }else{
                $sheetId=$mongoman->insertSingle("Entity_person_sheet",[]);
            }

            // Mise en bdd MySQL de l'ID de fiche de données
            $entityPerson->setSheetId($sheetId);
            $entityPerson->setAddDate(new \DateTime("now"));

            $em->persist($entityPerson);
            $em->flush();

            return $this->redirectToRoute('entity_people_index');
        }

        return $this->render('entity_people/new.html.twig', [
            'entity_person' => $entityPerson,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="manager/entity_people_show", methods="GET")
     */
    public function show(EntityPeople $entityPerson): Response
    {

        return $this->render('entity_people/show.html.twig', ['entity_person' => $entityPerson]);
    }

    /**
     * @Route("/{id}/edit", name="manager/entity_people_edit", methods="GET|POST")
     */
    public function edit(Request $request, EntityPeople $entityPerson): Response
    {
        $form = $this->createForm(EntityPeopleType::class, $entityPerson);
        $form->handleRequest($request);
        $mongoman = new MongoManager();

        if ($form->isSubmitted() && $form->isValid()) {
            if (null != $request->request->get('person_data')){
                $dataId=$entityPerson->getSheetId();
                foreach( $request->request->get('person_data') as $key->$value){
                    if ($value!=''){
                        $mongoman->updateSingleValueById("Entity_person_sheet",$dataId,$key,$value);
                    }else{
                        $mongoman->unsetSingleValueById("Entity_person_sheet",$dataId,$key);
                    }
                }
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entity_people_index', ['id' => $entityPerson->getId()]);
        }

        return $this->render('entity_people/edit.html.twig', [
            'entity_person' => $entityPerson,
            'form' => $form->createView(),
            'entity_person_data' => $mongoman->getDocById("Entity_person_sheet",$entityPerson->getSheetId()),
        ]);
    }

    /**
     * @Route("/{id}", name="manager/entity_people_delete", methods="DELETE")
     */
    public function delete(Request $request, EntityPeople $entityPerson): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entityPerson->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $mongoman = new MongoManager();
            $mongoman->deleteSingleById("Entity_person_sheet",$entityPerson->getSheetId());
            $em->remove($entityPerson);
            $em->flush();
        }

        return $this->redirectToRoute('entity_people_index');
    }
}
