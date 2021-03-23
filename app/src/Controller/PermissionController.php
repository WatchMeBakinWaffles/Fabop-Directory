<?php

namespace App\Controller;

use App\Entity\EntityPeople;
use App\Entity\EntityShows;
use App\Entity\EntityUser;
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

class PermissionController extends AbstractController
{
    /**
     * @Route("/permission", name="permission")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {

        $choiceEntity = array(
            'Personne' => EntityPeople::class,
            'Spectacle' => EntityShows::class,
            'Utilisateur'=> EntityUser::class
        );
        $useFilter = array(
            'Oui' => 'oui',
            'Non' => 'non',
        );

        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('nom_de_la_permission', TextType::class, array('required' => true))
            ->add('entite',ChoiceType::class, ['choices' => $choiceEntity])
            ->add('ajouter_un_filtre',ChoiceType::class, array(
            'choices' => $useFilter,
            'expanded' => false,
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (isset($data['ajouter_un_filtre']) && $data['ajouter_un_filtre'] === 'oui') {
                return $this->redirectToRoute('permission_filter', array('data' => $data));
            }
            else if (isset($data['ajouter_un_filtre']) && $data['ajouter_un_filtre'] === 'non') {
                return $this->redirectToRoute('permission_create', array('data' => $data));
            }
        }
        return $this->render('permission/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/permission_filter", name="permission_filter")
     * @param Request $request
     * @return Response
     */
    public function permission_filter(Request $request): Response
    {
        $data = $request->get('data');

        $choiceRights = array(
            'Oui' => 'oui',
            'Non' => 'non',
            'Inchangés' => 'inchanges',
        );
        $choiceField = array(
            'Email' => 'email',
            'Role' => 'role',
        );

        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('champ_a_filtrer', ChoiceType::class, array(
                'choices' => $em->getClassMetadata($data['entite']) ->getColumnNames(),
                'choice_label' => function ($value){
                    return $value;
                },))
            ->add('valeur_du_filtre', TextType::class, array('required' => true))
            ->add('droits_lecture',ChoiceType::class, [
            'choices' => $choiceRights,
            'expanded'=>false,
            ])
            ->add('droits_ecriture',ChoiceType::class, [
            'choices' => $choiceRights,
            'expanded'=>false,
            ])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = array_merge($data, $form->getData());
            return $this->redirectToRoute('permission_create', array('data' => $data));
        }

        return $this->render('permission/permission_filter.html.twig', ['form' => $form->createView(), 'data' => $data]);

            }


    /**
     * @Route("/permission_create", name="permission_create")
     * @param Request $request
     * @return Response
     */
    public function permission_create(Request $request): Response
    {
        $data = $request->get('data');
        $jsonData = new JsonResponse($data);

        var_dump($jsonData);

        return $this->render('permission/permission_create.html.twig', ['data' => $data]);

    }


}

