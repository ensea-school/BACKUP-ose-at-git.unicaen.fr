<?php

namespace Application\Service\Initializer;

use Application\Service\Intervenant as IntervenantService;

/**
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait IntervenantServiceAwareTrait
{
    /**
     * @var IntervenantService
     */
    protected $intervenantService;
    
    /**
     * Spécifie le service Intervenant.
     *
     * @param IntervenantService $intervenantService
     * @return self
     */
    public function setIntervenantService(IntervenantService $intervenantService = null)
    {
        $this->intervenantService = $intervenantService;
        
        return $this;
    }
    
    /**
     * Retourne le service Intervenant.
     *
     * @return IntervenantService
     */
    public function getIntervenantService()
    {
        return $this->intervenantService;
    }
}