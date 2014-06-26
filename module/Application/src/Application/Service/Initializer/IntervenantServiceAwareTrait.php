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
    protected $serviceIntervenant;
    
    /**
     * Spécifie le service Intervenant.
     *
     * @param IntervenantService $serviceIntervenant
     * @return self
     */
    public function setIntervenantService(IntervenantService $serviceIntervenant = null)
    {
        $this->serviceIntervenant = $serviceIntervenant;
        
        return $this;
    }
    
    /**
     * Retourne le service Intervenant.
     *
     * @return IntervenantService
     */
    public function getIntervenantService()
    {
        return $this->serviceIntervenant;
    }
}