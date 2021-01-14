<?php

namespace App\Form;

use App\Entity\EntityRole;
use App\Entity\Permissions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Utils\MongoManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EntityRolesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
/*
        $mongoman = new MongoManager();
        $try = "";
        $data_permissions = $mongoman->getDocById("permissions_user","5fb6aa76ff0f877fab155dd3");
        foreach($data_permissions as $perm => $droit)
        {
            $try .= $perm;}
        $choicesPerm = array(
            'rien' => ' ',
            'R' => 'R',
            'W'=> 'W',
            'RW' => 'RW'
        );*/
        $builder
            ->add('nom', null,array('required' => true))
            ->add(
                'users', 
                null,
                array(
                    'label' => 'user',
                    'attr' => array(
                        'class' => 'cm-input'
                        )
                    )
            )
            /*
            ->add(
                'users', 
                 CollectionType::class, array(
                    'entry_type'   => ChoiceType::class,
                    'entry_options'  => array(
                        'choices'  => array(
                            'Nashville' => 'nashville',
                            'Paris'     => 'paris',
                            'Berlin'    => 'berlin',
                            'London'    => 'london',
                        ),
                    ),
                    'data'  => array(
                        'try',
                        'two',
                    ),
                ))*/
            /*->add('editable', ChoiceType::class, array(
                'choices' => $choices,
                'multiple' => false,)
            )
            ->add('permissions' ,
            CollectionType::class, array(
                'entry_type'   => ChoiceType::class,
                'entry_options'  => array(
                    'choices'  => $choicesPerm,
                ),
                'data'  => array(
                    'shows'=> "shows",
                    'tags'=> "tags",
                    'peoples'=>'peoples',
                    'users'=>'users',
                    'models'=>'models',
                    'institutions'=>'institutions',
                    'roles'=>'roles',
                    'import',
                    'export',
                    'connection',
                    'restaurations'=>'restaurations'
                ),
            ))*/;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entityRoles' => EntityRoles::class,
        ]);
    }
}
