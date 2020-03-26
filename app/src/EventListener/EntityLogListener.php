<?php

namespace App\EventListener;
     
use App\Entity\EntityPeople;
use App\Entity\EntityInstitutions;
use App\Entity\EntityUser;
use App\Entity\Log;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;

class EntityLogListener
{
    private $securityContext;
    private $fields_people = ['name', 'firstname','birthdate', 'newsletter', 'postal_code', 'city', 'institution', 'adresseMailing'];
    private $fields_institution = ['name', 'role'];
    private $fields_user = ['lastName', 'firstName','email', 'role', 'Api_token',  'institution'];
    private $log = [];

    public function __construct(UsageTrackingTokenStorage $security)
    {
        $this->securityContext = $security;
    }

    private function log(array $data){
        // now
        $date = new \DateTime('',new \DateTimeZone('Europe/Paris'));

        $log = new Log();
        $log->setDate($date);
        $log->setElement($data['element']);
        $log->setTypeAction($data['type_action']);
        $log->setComment($data['comment']);
        $log->setIdUser($this->securityContext->getToken()->getUser()->getId());
        $log->setInstitution($data['institution']);

        $this->log[] = $log;
    }

    public function preUpdate(LifecycleEventArgs $args) 
    {
        $entity = $args->getEntity();


        if ($entity instanceof EntityPeople) {
            foreach ($this->fields_people as $field) {
                if ( $args->hasChangedField($field)){
                    $this->log(array(
                        'element' => 'Participant',
                        'type_action' => 'Modification',
                        'comment' => $field." : '".$args->getOldValue($field)."' => '".$args->getNewValue($field)."'",
                        'institution' => $entity->getInstitution()->getId()
                    ));
                }
            }
        }
        elseif ($entity instanceof EntityInstitutions) {
            foreach ($this->fields_institution as $field) {
                if ( $args->hasChangedField($field)){
                    $this->log(array(
                        'element' => 'Institution',
                        'type_action' => 'Modification',
                        'comment' => $field." : '".$args->getOldValue($field)."' => '".$args->getNewValue($field)."'",
                        'institution' => $entity->getInstitution()->getId()
                    ));
                }
            }
        }
        elseif ($entity instanceof EntityUser) {
            foreach ($this->fields_user as $field) {
                if ( $args->hasChangedField($field)){
                    $this->log(array(
                        'element' => 'Utilisateur',
                        'type_action' => 'Modification',
                        'comment' => $field." : '".$args->getOldValue($field)."' => '".$args->getNewValue($field)."'",
                        'institution' => $entity->getInstitution()->getId()
                    ));
                }
            }
        }
    }


    public function prePersist(LifecycleEventArgs $args) 
    {
        $entity = $args->getEntity();


        if ($entity instanceof EntityPeople) {
            
            $this->log(array(
                'element' => 'Participant',
                'type_action' => 'Ajout',
                'comment' => $this->securityContext->getToken()->getUser()->getEmail().' a ajouté '.$entity->getName().' '.$entity->getFirstname(),
                'institution' => $entity->getInstitution()->getId()
            ));                
            
        }
        elseif ($entity instanceof EntityInstitutions) {
            
            $this->log(array(
                'element' => 'Institution',
                'type_action' => 'Ajout',
                'comment' => $this->securityContext->getToken()->getUser()->getEmail().' a ajouté '.$entity->getName(),
                'institution' => $entity->getInstitution()->getId()
            ));  
                
            
        }
        elseif ($entity instanceof EntityUser) {
            
            $this->log(array(
                'element' => 'Utilisateur',
                'type_action' => 'Ajout',
                'comment' => $this->securityContext->getToken()->getUser()->getEmail().' a ajouté '.$entity->getFirstName().' '.$entity->getLastName(),
                'institution' => $entity->getInstitution()->getId()
            ));  
                
            
        }
    }

    public function preRemove(LifecycleEventArgs $args) 
    {
        $entity = $args->getEntity();

        //now
        $date = new \DateTime('',new \DateTimeZone('Europe/Paris'));

        if ($entity instanceof EntityPeople) {
            
            $this->log(array(
                'element' => 'Participant',
                'type_action' => 'Suppression',
                'comment' => $this->securityContext->getToken()->getUser()->getEmail().' a supprimé '.$entity->getName().' '.$entity->getFirstname(),
                'institution' => $entity->getInstitution()->getId()
            ));  
                
            
        }
        elseif ($entity instanceof EntityInstitutions) {
            
            $this->log(array(
                'element' => 'Institution',
                'type_action' => 'Suppression',
                'comment' => $this->securityContext->getToken()->getUser()->getEmail().' a supprimé '.$entity->getName(),
                'institution' => $entity->getInstitution()->getId()
            ));  
                
            
        }
        elseif ($entity instanceof EntityUser) {
            
            $this->log(array(
                'element' => 'Utilisateur',
                'type_action' => 'Suppression',
                'comment' => $this->securityContext->getToken()->getUser()->getEmail().' a supprimé '.$entity->getFirstName().' '.$entity->getLastName(),
                'institution' => $entity->getInstitution()->getId()
            ));  
                
            
        }
    }


    public function postFlush(PostFlushEventArgs $args)
    {
        if (! empty($this->log)) {
            $em = $args->getEntityManager();

            foreach ($this->log as $log) {
                $em->persist($log);
            }

            $this->log = [];
            $em->flush();
        }
    }
}