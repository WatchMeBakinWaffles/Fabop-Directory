<?php

namespace App\Controller;

use App\Entity\EntityTags;
use App\Form\EntityTagsType;
use App\Repository\EntityTagsRepository;
use App\Security\Voter\PermissionCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/manager/tags")
 */
class EntityTagsController extends AbstractController
{
    /**
     * @Route("/", name="entity_tags_index", methods="GET")
     */
    public function index(EntityTagsRepository $entityTagsRepository): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        //filtres à appliquer ici
        $list = PermissionCalculator::checkRight($user,"tags",$entityTagsRepository->findAll(),"read");
        $edit = PermissionCalculator::checkRight($user,"tags",$list,"write");
        return $this->render('entity_tags/index.html.twig', ['entity_tags' => $list, 'edits' => $edit]);
    }

    // TODO :: Quand Svelte Tableau mis en place, utiliser les webs components et l'API donc supprimer cette route
    /**
     * @Route("/list", name="entity_tags_list", methods="GET")
     */
    public function list(EntityTagsRepository $entityTagsRepository): Response
    {
        return $this->render('entity_tags/list.html.twig', ['entity_tags' => $entityTagsRepository->findAll()]);
    }

    /**
     * @Route("/new", name="entity_tags_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $entityTag = new EntityTags();
	if($this->isGranted('POST_EDIT',$entityTag)){
		$form = $this->createForm(EntityTagsType::class, $entityTag, array(
		    'attr' => array(
		        'id' => 'form_entity_tags_new',
		    )
		));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
		    $em = $this->getDoctrine()->getManager();
		    $em->persist($entityTag);
		    $em->flush();

		    return $this->redirectToRoute('entity_tags_index');
		}

		return $this->render('entity_tags/new.html.twig', [
		    'entity_tag' => $entityTag,
		    'form' => $form->createView(),
		]);
	}
	else{
		return $this->render('error403forbidden.html.twig');
	}
	return $this->redirectToRoute('entity_tags_index');
    }

    /**
     * @Route("/modal/new", name="entity_tags_new_modal", methods="GET|POST")
     */
    public function newModal(Request $request): Response
    {
        $entityTag = new EntityTags();
        $form = $this->createForm(EntityTagsType::class, $entityTag, array(
            'attr' => array(
                'id' => 'form_entity_tags_new',
            )
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entityTag);
            $em->flush();
        }

        return $this->render('modal/tag_new.html.twig', [
            'entity_tag' => $entityTag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entity_tags_show", methods="GET")
     */
    public function show(EntityTags $entityTag): Response
    {
	if($this->isGranted('POST_VIEW',$entityTag)){
        	return $this->render('entity_tags/show.html.twig', ['entity_tag' => $entityTag]);
	}
	else{
		return $this->render('error403forbidden.html.twig');
	}
	return $this->redirectToRoute('entity_tags_index');
    }

    /**
     * @Route("/{id}/edit", name="entity_tags_edit", methods="GET|POST")
     */
    public function edit(Request $request, EntityTags $entityTag): Response
    {
	if($this->isGranted('POST_EDIT',$entityUser)){
		$form = $this->createForm(EntityTagsType::class, $entityTag);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
		    $this->getDoctrine()->getManager()->flush();

		    return $this->redirectToRoute('entity_tags_index', ['id' => $entityTag->getId()]);
		}

		return $this->render('entity_tags/edit.html.twig', [
		    'entity_tag' => $entityTag,
		    'form' => $form->createView(),
		]);
	}
	else{
		return $this->render('error403forbidden.html.twig');
	}
        return $this->redirectToRoute('entity_tags_index');
    }

    /**
     * @Route("/{id}", name="entity_tags_delete", methods="DELETE")
     */
    public function delete(Request $request, EntityTags $entityTag): Response
    {
	if($this->isGranted('POST_EDIT',$entityUser)){
		if ($this->isCsrfTokenValid('delete'.$entityTag->getId(), $request->request->get('_token'))) {
		    $em = $this->getDoctrine()->getManager();
		    $em->remove($entityTag);
		    $em->flush();
		}
	}
	else{
		return $this->render('error403forbidden.html.twig');
	}
        return $this->redirectToRoute('entity_tags_index');
    }
}
