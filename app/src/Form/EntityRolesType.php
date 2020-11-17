<?php

namespace App\Form;

use App\Entity\EntityRole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class EntityRolesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            'oui' => 'oui',
            'non' => 'non',
        );
        $builder
            ->add('nom', null,array('required' => true))
            ->add('users', null,array('label' => 'user','attr' => array('class' => 'cm-input')))
            ->add('editable', ChoiceType::class, array(
                'choices' => $choices,
                'multiple' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entityRoles' => EntityRoles::class,
        ]);
    }
}
