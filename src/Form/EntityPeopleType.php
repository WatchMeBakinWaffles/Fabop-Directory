<?php

namespace App\Form;

use App\Entity\EntityPeople;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityPeopleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('firstname')
            ->add('birthdate')
            ->add('newsletter')
            ->add('postal_code')
            ->add('city')
            ->add('add_date')
            ->add('sheet_id')
            ->add('institution')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EntityPeople::class,
        ]);
    }
}
