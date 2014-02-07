<?php

namespace Import\Model\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Import\Model\Mapper\Mapper;

/**
 * Classe mère des services
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Service implements ServiceManagerAwareInterface {

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     *
     * @var type
     */
    protected $mapper;





    /**
     * Retourne le mapper à utiliser.
     *
     * @return Mapper
     */
    protected function getMapper()
    {
        if (null === $this->mapper) {
            $this->mapper = new Mapper();
            $this->mapper->setServiceManager( $this->getServiceManager() );
        }
        return $this->mapper;
    }

    /**
     * Get service manager
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     * @return self
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}