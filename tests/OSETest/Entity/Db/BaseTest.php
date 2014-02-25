<?php

namespace OSETest\Entity\Db;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use OSETest\Bootstrap;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;

/**
 * Description of BaseTest
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class BaseTest extends PHPUnit_Framework_TestCase
{
    protected $em;
    protected $eventm;
    protected $user;
    
    protected function setUp()
    {
        $em = $this->getEntityManager();
        
        if (!($this->user = $em->find("Application\Entity\Db\Utilisateur", $id = 1))) {
            $this->markTestIncomplete("Utilisateur (id = $id) introuvable.");
        }

        // recherche du listener de gestion de l'historique pour lui transmettre l'utilisateur
        foreach ($this->getEventManager()->getListeners() as $event => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof \Common\ORM\Event\Listeners\Histo) {
                    $listener->setIdentity(array('db' => $this->user));
                }
            }
        }
    }
    
    protected function tearDown()
    {
        
    }
    
    /**
     * 
     * @param string $name
     * @return EntityManager
     */
    protected function getEntityManager($name = 'orm_default')
    {
        if (null === $this->em) {
            $this->em = $this->getServiceManager()->get("doctrine.entitymanager.$name");
        }
        return $this->em;
    }
    
    /**
     * 
     * @param string $name
     * @return EventManager
     */
    protected function getEventManager($name = 'orm_default')
    {
        if (null === $this->eventm) {
            $this->eventm = $this->getServiceManager()->get("doctrine.eventmanager.$name");
        }
        return $this->eventm;
    }
    
    /**
     * Retourne le gestionnaire de service.
     * 
     * @return ServiceManager
     */
    protected function getServiceManager()
    {
       return Bootstrap::getServiceManager();
    }
}