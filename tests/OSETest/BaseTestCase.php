<?php

namespace OSETest;

use Doctrine\ORM\EntityManager;
use OSETest\Bootstrap;
use OSETest\Entity\Db\EntityProvider;
use PHPUnit_Framework_TestCase;
use Laminas\ServiceManager\ServiceManager;

/**
 * Classe mère abstraite de toutes nos classes de tests unitaires/fonctionnels.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class BaseTestCase extends PHPUnit_Framework_TestCase
{
    protected $em;
    protected $eventm;
    protected $user;

    /**
     * @var EntityProvider
     */
    protected $entityProvider;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $em = $this->getEntityManager();

        if (!($this->user = $em->find("Application\Entity\Db\Utilisateur", $id = 1))) {
            $this->markTestIncomplete("Utilisateur (id = $id) introuvable.");
        }

        // recherche du listener de gestion de l'historique pour lui transmettre l'utilisateur
        foreach ($this->getEventManager()->getListeners() as $event => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof \Common\ORM\Event\Listeners\HistoriqueListener) {
                    $listener->setIdentity(['db' => $this->user]);
                }
            }
        }
    }

    /**
     *
     * @param string $name
     * @return EntityProvider
     */
    public function getEntityProvider($name = 'orm_default')
    {
        if (null === $this->entityProvider) {
            $this->entityProvider = new EntityProvider($this->getEntityManager($name));
            $this->entityProvider->setTestClassName(get_class($this));
        }

        return $this->entityProvider;
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

    protected function tearDown()
    {
        parent::tearDown();
    }
}