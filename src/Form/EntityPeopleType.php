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
            ->add('name',null,array('label' => 'Nom'))
            ->add('firstname', null,array('label' => 'Prénom'))
            ->add('birthdate', null,array('label' => 'Date de naissance'))
            ->add('newsletter', null,array('label' => 'Abonnement Newsletter'))
            ->add('postal_code', null,array('label' => 'Code postal'))
            ->add('city', null,array('label' => 'Ville'))
            ->add('institution', null,array('label' => 'Institution'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EntityPeople::class,
        ]);
    }
}
