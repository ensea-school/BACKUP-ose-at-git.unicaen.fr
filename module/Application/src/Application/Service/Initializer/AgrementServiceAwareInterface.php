<?php

namespace Application\Service\Initializer;

use Application\Service\Agrement as AgrementService;

/**
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface AgrementServiceAwareInterface
{
    /**
     * Spécifie le service Agrement.
     *
     * @param AgrementService $service
     * @return self
     */
    public function setAgrementService(AgrementService $service);
    
    /**
     * Retourne le service Agrement.
     *
     * @return AgrementService
     */
    public function getAgrementService();
}