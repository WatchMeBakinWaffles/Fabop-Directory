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
            'utilisateur' => 'ROLE_USER',
        );
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, array(
                'choices' => $choices,
                'multiple' => true,
            ))
            ->add('password', PasswordType::class)
            ->add('firstName')
            ->add('lastName')
            ->add('ApiToken')
        ;
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    // transform the array to a string
                    return implode(', ', $tagsAsArray);
                },
                function ($tagsAsString) {
                    // transform the string back to an array
                    return explode(', ', $tagsAsString);
                }
            ))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EntityUser::class,
        ]);
    }
}
