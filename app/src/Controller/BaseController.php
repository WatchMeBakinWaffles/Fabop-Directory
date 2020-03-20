<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\EntityInstitutionsRepository;
use App\Repository\EntityPeopleRepository;
use App\Repository\EntityPerformancesRepository;
use App\Repository\EntityShowsRepository;
use App\Repository\EntityTagsRepository;
use App\Repository\EntityTagsAffectRepository;
use App\Repository\EntityUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\MongoManager;
use App\Entity\EntityUser;
use App\Form\EntityUserProfileType;


/**
 * @Route("/")
 */
class BaseController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard", methods="GET")
     */
    public function index(
        EntityInstitutionsRepository $entityInstitutionsRepository,
        EntityPeopleRepository $entityPeopleRepository,
        EntityPerformancesRepository $entityPerformancesRepository,
        EntityShowsRepository $entityShowsRepository,
        EntityTagsRepository $entityTagsRepository
    ): Response
    {
        return $this->render('dashboard.html.twig',
            [
                'entity_institutions_count' => $entityInstitutionsRepository->count([]),
                'entity_people_count' => $entityPeopleRepository->count([]),
                'entity_performances_count' => $entityPerformancesRepository->count([]),
                'entity_shows_count' => $entityShowsRepository->count([]),
                'entity_tags_count' => $entityTagsRepository->count([])
            ]
        );
    }


    /**
     * @Route("/profile/edit/{id}", name="profile_user_edit", methods={"GET","POST"})
     */
    public function edit_profile(Request $request, EntityUser $entityUser): Response
    {
        $form = $this->createForm(EntityUserProfileType::class, $entityUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entityUser);
            /**
             * Hashage du mot de passe avec le protocole BCRYPT juste avant l'enregistrement en bd.
             */
            $entityUser->bCryptPassword($entityUser->getPassword());
            $entityManager->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('entity_user/profile_edit.html.twig', [
            'entity_user' => $entityUser,
            'form' => $form->createView(),
        ]);
    }

}
