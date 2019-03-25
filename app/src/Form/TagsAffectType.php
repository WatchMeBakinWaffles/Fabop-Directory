<?php

namespace App\Form;

use App\Entity\TagsAffect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagsAffectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tag', null, array('label' => 'Tag'))
            ->add('performance', null, array('label' => 'Représentation'))
            ->add('person', null, array('label' => 'Personne'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TagsAffect::class,
        ]);
    }
}
