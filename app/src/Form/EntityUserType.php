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
use Symfony\Component\Security\Core\Security;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\EntityRoles;
use App\Utils\MongoManager;
use App\Repository\EntityRolesRepository;


class EntityUserType extends AbstractType
{
    public function __construct(Security $security,EntityRolesRepository $entityRolesRepository)
    {
        $this->security = $security;
        $this->entityRolesRepository = $entityRolesRepository;

       // $test= $this->entityRolesRepository.find($this->choix);
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*exemple de builder pour recuperer tous les nom de category
        $builder->add('category', EntityType::class, [
        'class' => Category::class,
        'choice_label' => function ($category) {
        return $category->getDisplayName();
    }
]);
        */

        /*
        ancien choix

        $choices = array(
            'administrateur' => 'ROLE_ADMIN',
            'contributeur' => 'ROLE_CONTRIBUTEUR',
            'utilisateur' => 'ROLE_USER',
        );
        */

        $em = $this->entityRolesRepository;
        $builder
            ->add('email', null,array('required' => true))

            ->add('entityRoles', EntityType::class, array(
                'class' =>EntityRoles::class, 
                'multiple'=>true,
                'choice_value' => 'nom',
                'choice_label' => function ($em) {
                    return $em->getNom();
                }
            ))
           // ->add('roles', ChoiceType::class, array('choices' => $choice,'multiple' => true,))
            ->add('password', PasswordType::class)
            ->add('firstName')
            ->add('lastName')
            ->add('ApiToken')
        ;
        if($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('institution', null,array('label' => 'Institution','attr' => array('class' => 'cm-input')));
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entityUser' => EntityUser::class,
        ]);
    }
}
