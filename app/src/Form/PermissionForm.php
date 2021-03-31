<?php

namespace App\Form;

use App\Entity\EntityInstitutions;
use App\Entity\EntityModele;
use App\Entity\EntityPeople;
use App\Entity\EntityShows;
use App\Entity\EntityTags;
use App\Entity\EntityUser;
use App\Entity\EntityUserPermissions;
use App\Exception\DocumentNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Security\Core\Security;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\EntityRoles;
use App\Utils\MongoManager;
use App\Repository\EntityRolesRepository;


class PermissionForm extends AbstractType
{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choiceEntity = array(
            'Institution' => EntityInstitutions::class,
            'Modèle' => EntityModele::class,
            'Personne' => EntityPeople::class,
            'Rôle' => EntityRoles::class,
            'Spectacle' => EntityShows::class,
            'Tag' => EntityTags::class,
            'Utilisateur' => EntityUser::class,
        );

        $useFilter = array(
            'Non' => 'non',
            'Oui' => 'oui',
        );
        $builder
            ->add('nom_de_la_permission', TextType::class, array('required' => true))
            ->add('entite', ChoiceType::class, ['choices' => $choiceEntity])
            ->add('ajouter_un_filtre', ChoiceType::class, array(
                'choices' => $useFilter,
                'expanded' => true,
                'required' => true
            ))
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
                        ->add('+', ButtonType::class, [
                            'attr' => ['class' => 'btn btn-primary m-1 add_custom_data']
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
