<?php

namespace App\Controller;

use App\Entity\EntityModele;
use App\Entity\EntityUser;
use App\Form\EntityModeleType;
use App\Repository\EntityModeleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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
        return $this->render('entity_modeles/index.html.twig', ['entity_modeles' => $entityModeleRepository->findAll()]);
    }

    /**
     * @Route("/new", name="entity_modeles_new", methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $userName = $this->getUser()->getUsername();

        $user = $this->getDoctrine()
            ->getRepository(EntityUser::class)
            ->findOneByEmail($userName);

        $modele = new EntityModele();
        $modele->addUser($user);
        $form = $this->createForm(EntityModeleType::class, $modele);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($modele);
            $em->flush();
            return $this->redirectToRoute('entity_modele_index');
        }

        return $this->render('entity_modeles/new.html.twig', [
            "form" => $form->createView(),
            "entity_modeles" => $modele,
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