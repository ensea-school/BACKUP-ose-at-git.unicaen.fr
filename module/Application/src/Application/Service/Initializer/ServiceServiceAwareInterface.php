<?php

namespace Application\Service\Initializer;

use Application\Service\Service as ServiceService;

/**
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface ServiceServiceAwareInterface
{
    /**
     * Spécifie le service Service.
     *
     * @param ServiceService $service
     * @return self
     */
    public function setServiceService(ServiceService $service);
    
    /**
     * Retourne le service Service.
     *
     * @return ServiceService
     */
    public function getServiceService();
}