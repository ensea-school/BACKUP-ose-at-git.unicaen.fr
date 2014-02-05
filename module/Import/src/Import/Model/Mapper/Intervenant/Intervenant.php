<?php

namespace Import\Model\Mapper\Intervenant;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

abstract class Intervenant implements ServiceManagerAwareInterface {


    /**
     * @var ServiceManager
     */
    protected $serviceManager;





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

    /**
     * recherche un ensemble d'enseignants
     *
     * @param string $term
     * @return array[]
     */
    abstract public function search( $term, $limit=100 );

}