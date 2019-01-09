<?php

namespace OSETest\Controller;

use OSETest\Bootstrap;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Classe mère des tests de contrôleur.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class BaseTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    protected $em;
    
    /**
     * 
     */
    public function setUp()
    {
        $this->setApplicationConfig(include CONFIG_DIR . '/application.config.php');
        parent::setUp();
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
     * Retourne le gestionnaire de service.
     * 
     * @return ServiceManager
     */
    protected function getServiceManager()
    {
       return Bootstrap::getServiceManager();
    }
}