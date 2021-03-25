<?php

namespace App\Form;

use App\Entity\EntityUser;
use App\Entity\EntityUserPermissions;
use App\Exception\DocumentNotFoundException;
use Symfony\Component\Form\AbstractType;
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


class EntityUserType extends AbstractType
{
    public function __construct(Security $security,EntityRolesRepository $entityRolesRepository)
    {
        $this->security = $security;
        $this->entityRolesRepository = $entityRolesRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $mongoman = new MongoManager();

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
            ));
           try {
               $data_permissions = $mongoman->getAllPermission("permissions_user");
               $builder->add('permissions', ChoiceType::class, array(
                   'multiple'=>true,
                   "mapped" => false,
                   'choices' => $data_permissions,
                   'choice_label' => function ($em) {
                       return $em['label'];
                   }
               ));
           } catch (DocumentNotFoundException $e) {
               return $e;
           }
           $builder->add('password', PasswordType::class, array( 'required' => false))
            ->add('firstName')
            ->add('lastName')
            ->add('ApiToken')
           ->addEventListener(FormEvents::PRE_SUBMIT,function(FormEvent $event) {
               $requestData = $event->getData();
               $user = $event->getForm()->getData();
               if ($user && !$requestData['password']){
                   $requestData['password'] = $user->getPassword();
               }
               $event->setData($requestData);
           });

        if($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('institution', null,array('label' => 'Institution','attr' => array('class' => 'cm-input')));
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entityUser' => EntityUser::class,
            'allow_extra_fields' => true,
        ]);
    }
}
