<?php

namespace Application\Service\Initializer;

use Application\Service\Service as ServiceService;

/**
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait ServiceServiceAwareTrait
{
    /**
     * @var ServiceService
     */
    protected $serviceService;
    
    /**
     * Spécifie le service Service.
     *
     * @param ServiceService $service
     * @return self
     */
    public function setServiceService(ServiceService $service = null)
    {
        $this->serviceService = $service;
        
        return $this;
    }
    
    /**
     * Retourne le service Service.
     *
     * @return ServiceService
     */
    public function getServiceService()
    {
        return $this->serviceService;
    }
}