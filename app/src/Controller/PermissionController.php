<?php

namespace App\Controller;

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
            'Chat' => 'cat',
            'Chien' => 'dog',
            'Tortue'=> 'turtle',
            'Poisson' => 'fish'
        );
        $useFilter = array(
            'Oui' => 'oui',
            'Non' => 'non',
        );

        $form = $this->createFormBuilder()
            ->add('nom_de_la_permission', TextType::class, array('required' => true))
            ->add('entite',ChoiceType::class, ['choices' => $choiceEntity])
            ->add('ajouter_un_filtre',ChoiceType::class, array(
                //'dataclass' => 'useFilter',
                'choices' => $useFilter,
                'expanded' => false,
            ))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (isset($data['ajouter_un_filtre']) && $data['ajouter_un_filtre'] === 'oui') {
                return $this->redirectToRoute('permission_filter', $data);
            }
            else if (isset($data['ajouter_un_filtre']) && $data['ajouter_un_filtre'] === 'non') {
                return $this->render('permission/permission_created.html.twig', $data);
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
        $choiceRights = array(
            'Oui' => 'oui',
            'Non' => 'non',
            'Inchangés' => 'inchanges',
        );
        $choiceField = array(
            'Email' => 'email',
            'Role' => 'role',
        );

        $form = $this->createFormBuilder()
            ->add('champ_a_filtrer',ChoiceType::class, [
            'choices' => $choiceField,
            'expanded'=>false,
            ])
            ->add('valeur_du_filtre', null, array('required' => true))
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

        return $this->render('permission/permission_filter.html.twig', [
            'form' => $form->createView(),
        ]);
            }

}
