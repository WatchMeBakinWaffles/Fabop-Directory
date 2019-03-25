<?php

namespace App\Form;

use App\Entity\EntityPerformances;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityPerformancesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', null, array('label' => 'Date'))
            ->add('shows', null, array('label' => 'Spectacle'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EntityPerformances::class,
        ]);
    }
}
