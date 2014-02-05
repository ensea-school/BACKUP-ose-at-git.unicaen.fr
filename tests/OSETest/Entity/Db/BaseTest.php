<?php

namespace OSETest\Entity\Db;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
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
    
    /**
     * Évite d'avoir à faire ->setHistoCreateur(1)->setHistoModificateur(1)
     * sur toutes les entités créées.
     * 
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $args->getEntity()
                ->setHistoCreateur(1)
                ->setHistoModificateur(1);
    }
    
    protected function setUp()
    {
        $this->getEventManager()->addEventListener(Events::prePersist, $this);
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