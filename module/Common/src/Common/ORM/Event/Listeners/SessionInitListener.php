<?php

namespace Common\ORM\Event\Listeners;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Events;
use Doctrine\Common\EventSubscriber;

/**
 * Transmission au package Oracle de l'identifiant de l'utilisateur connecté.
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class SessionInitListener implements EventSubscriber, ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $sl;
    
    /**
     * @var mixed
     */
    protected $identity;
    
    /**
     * @param \Doctrine\DBAL\Event\ConnectionEventArgs $args
     *
     * @return void
     */
    public function postConnect(ConnectionEventArgs $args)
    {
        $user = null;
        
        // l'utilisateur connecté est transmis au package Oracle
        if (($identity = $this->getIdentity())) {
            if (isset($identity['db']) && $identity['db'] instanceof \Application\Entity\Db\Utilisateur) {
                $user = $identity['db']; /* @var $user \Application\Entity\Db\Utilisateur */
            }
        }
        
        if (null === $user) {
            return;
        }
        
        $service = $this->sl->get('importProcessusImport'); /* @var $service \Import\Processus\Import */
        $service->setCurrentUser($user->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(Events::postConnect);
    }
    
    /**
     * 
     * @param mixed $identity
     * @return self
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        
        return $this;
    }
    
    /**
     * 
     * @return mixed
     */
    public function getIdentity()
    {
        if (null === $this->identity) {
            $authenticationService = $this->sl->get('Zend\Authentication\AuthenticationService');
            if ($authenticationService->hasIdentity()) {
                $this->identity = $authenticationService->getIdentity();
            }
        }
        
        return $this->identity;
    }    
    
    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return self
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->sl = $serviceLocator;
        
        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->sl;
    }
}
