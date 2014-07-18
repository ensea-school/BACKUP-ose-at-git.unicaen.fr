<?php

namespace Application\Service\Initializer;

use Application\Service\Intervenant as IntervenantService;

/**
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface IntervenantServiceAwareInterface
{
    /**
     * Spécifie le service Intervenant.
     *
     * @param IntervenantService $intervenantService
     * @return self
     */
    public function setIntervenantService(IntervenantService $intervenantService);
    
    /**
     * Retourne le service Intervenant.
     *
     * @return IntervenantService
     */
    public function getIntervenantService();
}