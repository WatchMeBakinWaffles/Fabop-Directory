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

    public function preUpdate(LifecycleEventArgs $args) 
    {
        $entity = $args->getEntity();

        // now
        $date = new \DateTime('',new \DateTimeZone('Europe/Paris'));

        if ($entity instanceof EntityPeople) {
            foreach ($this->fields_people as $field) {
                if ( $args->hasChangedField($field)){
                    $log = new Log();
                    $log->setDate($date);
                    $log->setElement('Participant');
                    $log->setTypeAction('Modification');
                    $log->setComment($field." : '".$args->getOldValue($field)."' => '".$args->getNewValue($field)."'");
                    $log->setIdUser($this->securityContext->getToken()->getUser()->getId());

                    $this->log[] = $log;
                }
            }
        }
        elseif ($entity instanceof EntityInstitutions) {
            foreach ($this->fields_institution as $field) {
                if ( $args->hasChangedField($field)){
                    $log = new Log();
                    $log->setDate($date);
                    $log->setElement('Institution');
                    $log->setTypeAction('Modification');
                    $log->setComment($field." : '".$args->getOldValue($field)."' => '".$args->getNewValue($field)."'");
                    $log->setIdUser($this->securityContext->getToken()->getUser()->getId());

                    $this->log[] = $log;
                }
            }
        }
        elseif ($entity instanceof EntityUser) {
            foreach ($this->fields_user as $field) {
                if ( $args->hasChangedField($field)){
                    $log = new Log();
                    $log->setDate($date);
                    $log->setElement('Utilisateur');
                    $log->setTypeAction('Modification');
                    $log->setComment($field." : '".$args->getOldValue($field)."' => '".$args->getNewValue($field)."'");
                    $log->setIdUser($this->securityContext->getToken()->getUser()->getId());

                    $this->log[] = $log;
                }
            }
        }
    }


    public function prePersist(LifecycleEventArgs $args) 
    {
        $entity = $args->getEntity();

        //now
        $date = new \DateTime('',new \DateTimeZone('Europe/Paris'));

        if ($entity instanceof EntityPeople) {
            
            $log = new Log();
            $log->setDate($date);
            $log->setElement('Participant');
            $log->setTypeAction('Ajout');
            $log->setComment($this->securityContext->getToken()->getUser()->getEmail());
            $log->setIdUser($this->securityContext->getToken()->getUser()->getId());

            $this->log[] = $log;
                
            
        }
        elseif ($entity instanceof EntityInstitutions) {
            
            $log = new Log();
            $log->setDate($date);
            $log->setElement('Institution');
            $log->setTypeAction('Ajout');
            $log->setComment($this->securityContext->getToken()->getUser()->getEmail());
            $log->setIdUser($this->securityContext->getToken()->getUser()->getId());

            $this->log[] = $log;
                
            
        }
        elseif ($entity instanceof EntityUser) {
            
            $log = new Log();
            $log->setDate($date);
            $log->setElement('Utilisateur');
            $log->setTypeAction('Ajout');
            $log->setComment($this->securityContext->getToken()->getUser()->getEmail());
            $log->setIdUser($this->securityContext->getToken()->getUser()->getId());

            $this->log[] = $log;
                
            
        }
    }

    public function preRemove(LifecycleEventArgs $args) 
    {
        $entity = $args->getEntity();

        //now
        $date = new \DateTime('',new \DateTimeZone('Europe/Paris'));

        if ($entity instanceof EntityPeople) {
            
            $log = new Log();
            $log->setDate($date);
            $log->setElement('Participant');
            $log->setTypeAction('Suppression');
            $log->setComment($this->securityContext->getToken()->getUser()->getEmail());
            $log->setIdUser($this->securityContext->getToken()->getUser()->getId());

            $this->log[] = $log;
                
            
        }
        elseif ($entity instanceof EntityInstitutions) {
            
            $log = new Log();
            $log->setDate($date);
            $log->setElement('Institution');
            $log->setTypeAction('Suppression');
            $log->setComment($this->securityContext->getToken()->getUser()->getEmail());
            $log->setIdUser($this->securityContext->getToken()->getUser()->getId());

            $this->log[] = $log;
                
            
        }
        elseif ($entity instanceof EntityUser) {
            
            $log = new Log();
            $log->setDate($date);
            $log->setElement('Utilisateur');
            $log->setTypeAction('Suppression');
            $log->setComment($this->securityContext->getToken()->getUser()->getEmail());
            $log->setIdUser($this->securityContext->getToken()->getUser()->getId());

            $this->log[] = $log;
                
            
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