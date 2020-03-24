<?php

namespace App\Form;

use App\Entity\EntityUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;


class EntityUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    

        $choices = array(
            'administrateur' => 'ROLE_ADMIN',
            'contributeur' => 'ROLE_CONTRIBUTEUR',
            'utilisateur' => 'ROLE_USER',
        );
        $builder
            ->add('email', null,array('required' => true))
            ->add('roles', ChoiceType::class, array(
                'choices' => $choices,
                'multiple' => true,
            ))
            ->add('password', PasswordType::class)
            ->add('firstName')
            ->add('lastName')
            ->add('institution', null,array('label' => 'Institution','attr' => array('class' => 'cm-input')))
            ->add('ApiToken')
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entityUser' => EntityUser::class,
        ]);
    }
}
