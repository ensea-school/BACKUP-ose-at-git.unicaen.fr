<?php

namespace Application\Service\Initializer;

use Application\Service\Agrement as AgrementService;

/**
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait AgrementServiceAwareTrait
{
    /**
     * @var AgrementService
     */
    protected $serviceAgrement;
    
    /**
     * Spécifie le service Agrement.
     *
     * @param AgrementService $service
     * @return self
     */
    public function setAgrementService(AgrementService $service = null)
    {
        $this->serviceAgrement = $service;
        
        return $this;
    }
    
    /**
     * Retourne le service Agrement.
     *
     * @return AgrementService
     */
    public function getAgrementService()
    {
        return $this->serviceAgrement;
    }
}