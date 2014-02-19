<?php

namespace Application;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\Event;
use Application\Entity\Db\Utilisateur;
use Doctrine\ORM\EntityManager;

/**
 * Description of AuthenticatedUserSavedListener
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AuthenticatedUserSavedListener implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();
 
    /**
     * @var EntityManager
     */
    protected $em;
    
    /**
     * 
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        $this->listeners[] = $sharedEvents->attach(
                'UnicaenAuth\Service\User', 
                \UnicaenAuth\Service\User::EVENT_USER_AUTHENTICATED_PRE_PERSIST, 
                array($this, 'onUserAuthenticatedPrePersist'), 
                100);
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
    
    /**
     * Renseigne les relations 'intervenant' et 'personnel' avant que l'objet soit persisté.
     * 
     * @param Event $e
     */
    public function onUserAuthenticatedPrePersist(Event $e)
    {
        $user       = $e->getParam('entity');     /* @var $user       \ZfcUser\Entity\UserInterface */
        $ldapPeople = $e->getParam('ldapPeople'); /* @var $ldapPeople \UnicaenApp\Entity\Ldap\People */
        
        if ($user instanceof User) {
            $repo = $this->em->getRepository('Application\Entity\Db\Personnel');
            $personnel = $repo->findOneBy(array('sourceCode' => $ldapPeople->getSupannEmpId()));
            
            $repo = $this->em->getRepository('Application\Entity\Db\Intervenant');
            $intervenant = $repo->findOneBy(array('sourceCode' => $ldapPeople->getSupannEmpId()));
            
            $user->setPersonnel($personnel)
                    ->setIntervenant($intervenant);
        }
    }
//    
//    /**
//     * 
//     * @param \Doctrine\ORM\EntityManager $em
//     * @return \Application\AuthenticatedUserSavedListener
//     */
//    public function setEntityManager(\Doctrine\ORM\EntityManager $em)
//    {
//        $this->em = $em;
//        return $this;
//    }
}