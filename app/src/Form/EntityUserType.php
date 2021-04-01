<?php

namespace App\Form;

use App\Entity\EntityUser;
use App\Entity\EntityUserPermissions;
use App\Exception\DocumentNotFoundException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
        $em = $this->entityRolesRepository;
        $builder
            ->add('email', EmailType::class,array('required' => true))
            ->add('entityRoles', EntityType::class, array(
                'class' =>EntityRoles::class,
                'multiple'=>true,
                'choice_value' => 'nom',
                'choice_label' => function ($em) {
                    return $em->getNom();
                },
                "label" => 'Rôles'

            ))
            //event listener pour récupérer les data deja présente dans la bdd
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $mongoman = new MongoManager();
            $form = $event->getForm();
            $t = array();

            foreach ($event->getData()->getEntityUserPermissions()->toArray() as $re) {
                array_push($t, $re->getSheetId());
            }
            //on affiche les permisssions disponibles
            try {
                $data_permissions = $mongoman->getAllPermission("permissions_user");
                $res = array();
               foreach ($event->getData()->getEntityUserPermissions() as $p) {
                   $t = $p->getSheetId();
                   $res += array_filter($data_permissions, function($v, $k) use ($t){
                       return $v['_id'] == $t;
                   }, ARRAY_FILTER_USE_BOTH);
               }
                $form->add('permissions', ChoiceType::class, array(
                    "multiple"=>true,
                    "mapped" => false,
                    "choices" => $data_permissions,
                    "choice_label" => function ($em) {
                        return $em['label'];
                    },
                    "data" => $res
                ));
            } catch (DocumentNotFoundException $e) {
                return $e;
            }
        })
            ->add('firstName',TextType::class, ["label" => "Prénom"])
            ->add('lastName',TextType::class, ["label" => "Nom"])
            ->add('ApiToken')
           ->addEventListener(FormEvents::PRE_SUBMIT,function(FormEvent $event) {
               $requestData = $event->getData();
               $user = $event->getForm()->getData();
               if ($user && !$requestData['password']){
                   $requestData['password'] = $user->getPassword();
               }
               $event->setData($requestData);
               foreach($user->getEntityRoles() as $RoleARetirer){
                   $user->removeEntityRole($RoleARetirer);
               }
           });
        if($options['extra_fields_message'] === 'edit') {
            $builder->add('password', PasswordType::class, ["label" => "Mot de passe", "required" => false]);
        } else {
            $builder->add('password', PasswordType::class, ["label" => "Mot de passe"]);
        }

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
