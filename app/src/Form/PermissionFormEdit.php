<?php

namespace App\Form;

use App\Entity\EntityInstitutions;
use App\Entity\EntityModele;
use App\Entity\EntityPeople;
use App\Entity\EntityRoles;
use App\Entity\EntityShows;
use App\Entity\EntityTags;
use App\Entity\EntityUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PermissionFormEdit extends AbstractType
{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //var_dump($builder->getData()['permissions'][0]['rights']);
        $data = $builder->getData();
        $useFilter = array(
            'Non' => 'non',
            'Oui' => 'oui',
        );
        $choiceRights = array(
            'Oui' => 'oui',
            'Non' => 'non',
            'Inchangés' => 'inchanges',
        );
        $classTraduction = array(
            'institutions' => EntityInstitutions::class,
            'models' => EntityModele::class,
            'peoples' => EntityPeople::class,
            'roles' => EntityRoles::class,
            'show' => EntityShows::class,
            'tags' => EntityTags::class,
            'users' => EntityUser::class,
        );
        $choiceTraduction = array(
            1 => 'oui',
            -1 => 'non',
            0 => 'inchanges'
        );


        $builder
            ->add('nom_de_la_permission', TextType::class, array('required' => true, "data" => $data['label'] ))
            ->add('entite', TextType::class, [
                'data' => $data['permissions']['entityType'],
                'disabled' => true]);
            $em = $this->entityManager;
            $i = 0;
            foreach ($data['permissions'][0]['rights'] as $right) {
                $i++;
                var_dump($right['filters'][0]['field']);
                $builder->add('champ_a_filtrer'.$i, ChoiceType::class, [
                    'choices' => $em->getClassMetadata( $classTraduction[$data['permissions']['entityType']])->getColumnNames(),
                    'choice_label' => function ($value) {
                        return $value;
                    },
                    'data' => $right['filters'][0]['field'],
                    'label' => 'Champ à filtrer'])
                    ->add('valeur_du_filtre'.$i, TextType::class, array(
                        'required' => true,
                        'label' => 'Valeur du filtre',
                        'data' => $right['filters'][0]['value']))
                    ->add('droits_lecture'.$i, ChoiceType::class, [
                        'choices' => $choiceRights,
                        'expanded' => false,
                        'label' => 'Droit de lecture',
                        'empty_data' => $choiceTraduction[$right['read']]
                    ])
                    ->add('droits_ecriture'.$i, ChoiceType::class, [
                        'choices' => $choiceRights,
                        'expanded' => false,
                        'label' => "Droit d'écriture",
                        'empty_data' => $choiceTraduction[$right['write']]
                    ])
                   ;
            }
            $builder->add('plus', ButtonType::class, [
                'attr' => ['class' => 'btn btn-primary m-1 add_custom_data'],
                'label' => 'Ajouter un un autre filtre'
            ])

        ;
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $user = $event->getData();
                $choiceRights = array(
                    'Oui' => 'oui',
                    'Non' => 'non',
                    'Inchangés' => 'inchanges',
                );
                $em = $this->entityManager;
                if (isset($user['ajouter_un_filtre']) && $user['ajouter_un_filtre'] === 'oui') {
                    $form->add('champ_a_filtrer0',ChoiceType::class, [
                        'choices' => $em->getClassMetadata($user['entite'])->getColumnNames(),
                        'choice_label' => function ($value) {
                            return $value;
                        },
                        'label' => 'Champ à filtrer'])
                        ->add('valeur_du_filtre0', TextType::class, array('required' => true, 'label' => 'Valeur du filtre'))
                        ->add('droits_lecture0', ChoiceType::class, [
                            'choices' => $choiceRights,
                            'expanded' => false,
                             'label' => 'Droit de lecture'
                        ])
                        ->add('droits_ecriture0', ChoiceType::class, [
                            'choices' => $choiceRights,
                            'expanded' => false,
                            'label' => "Droit d'écriture"
                        ])
                        ->add('plus', ButtonType::class, [
                            'attr' => ['class' => 'btn btn-primary m-1 add_custom_data'],
                            'label' => 'Ajouter un un autre filtre'
                        ])
                   ;
                }}
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);
    }
}
