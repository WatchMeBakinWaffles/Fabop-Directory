<?php

namespace App\Controller;

use App\Entity\EntityShows;
use App\Form\EntityShowsType;
use App\Repository\EntityShowsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\MongoManager;

/**
 * @Route("/manager/shows")
 */
class EntityShowsController extends AbstractController
{
    /**
     * @Route("/", name="entity_shows_index", methods="GET")
     */
    public function index(EntityShowsRepository $entityShowsRepository): Response
    {
        //filtres à appliquer ici
        return $this->render('entity_shows/index.html.twig', ['entity_shows' => $entityShowsRepository->findAll()]);
    }

    /**
     * @Route("/new", name="entity_shows_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $entityShow = new EntityShows();
	if($this->isGranted('POST_EDIT',$entityShow)){
		$form = $this->createForm(EntityShowsType::class, $entityShow);
		$form->handleRequest($request);
		$mongoman = new MongoManager();

		if ($form->isSubmitted() && $form->isValid()) {
		    $em = $this->getDoctrine()->getManager();

		    // Mise en bdd Mongo de l fiche doc --> return IdMongo
		    if (null != $request->request->get('show_data')){
		        $sheetId=$mongoman->insertSingle("Entity_show_sheet",$request->request->get('show_data'));
		    }else{
		        $sheetId=$mongoman->insertSingle("Entity_show_sheet",[]);
		    }

		    // Mise en bdd MySQL de l'ID de fiche de données
		    $entityShow->setSheetId($sheetId);

		    $em->persist($entityShow);
		    $em->flush();

		    return $this->redirectToRoute('entity_shows_index');
		}

		return $this->render('entity_shows/new.html.twig', [
		    'entity_show' => $entityShow,
		    'form' => $form->createView(),
		]);
	}
	return $this->redirectToRoute('entity_shows_index');
    }

    /**
     * @Route("/{id}", name="entity_shows_show", methods="GET")
     */
    public function show(EntityShows $entityShow): Response
    {
	if($this->isGranted('POST_VIEW',$entityShow)){
        	return $this->render('entity_shows/show.html.twig', ['entity_show' => $entityShow]);
	}
	return $this->redirectToRoute('entity_shows_index');
    }

    /**
     * @Route("/{id}/edit", name="entity_shows_edit", methods="GET|POST")
     */
    public function edit(Request $request, EntityShows $entityShow): Response
    {
	if($this->isGranted('POST_EDIT',$entityShow)){
		$form = $this->createForm(EntityShowsType::class, $entityShow);
		$form->handleRequest($request);
		$mongoman = new MongoManager();

		if ($form->isSubmitted() && $form->isValid()) {
		    if (null != $request->request->get('show_data')){
		        $dataId=$entityShow->getSheetId();
		        foreach( $request->request->get('show_data') as $key->$value){
		            if ($value!=''){
		                $mongoman->updateSingleValueById("Entity_show_sheet",$dataId,$key,$value);
		            }else{
		                $mongoman->unsetSingleValueById("Entity_show_sheet",$dataId,$key);
		            }
		        }
		    }

		    $this->getDoctrine()->getManager()->flush();

		    return $this->redirectToRoute('entity_shows_index', ['id' => $entityShow->getId()]);
		}

		return $this->render('entity_shows/edit.html.twig', [
		    'entity_show' => $entityShow,
		    'form' => $form->createView(),
		    'entity_show_data' => $mongoman->getDocById("Entity_show_sheet",$entityShow->getSheetId()),
		]);
	}
	return $this->redirectToRoute('entity_shows_index');
    }

    /**
     * @Route("/{id}", name="entity_shows_delete", methods="DELETE")
     */
    public function delete(Request $request, EntityShows $entityShow): Response
    {
	if($this->isGranted('POST_EDIT',$entityShow)){
		if ($this->isCsrfTokenValid('delete'.$entityShow->getId(), $request->request->get('_token'))) {
		    $em = $this->getDoctrine()->getManager();
		    $mongoman = new MongoManager();
		    $mongoman->deleteSingleById("Entity_show_sheet",$entityShow->getSheetId());
		    $em->remove($entityShow);
		    $em->flush();
		}
	}

        return $this->redirectToRoute('entity_shows_index');
    }
}
