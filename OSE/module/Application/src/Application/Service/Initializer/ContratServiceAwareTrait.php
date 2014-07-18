<?php

namespace Application\Service\Initializer;

use Application\Service\Contrat as ContratService;

/**
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait ContratServiceAwareTrait
{
    /**
     * @var ContratService
     */
    protected $serviceContrat;
    
    /**
     * Spécifie le service Contrat.
     *
     * @param ContratService $serviceContrat
     * @return self
     */
    public function setContratService(ContratService $serviceContrat = null)
    {
        $this->serviceContrat = $serviceContrat;
        
        return $this;
    }
    
    /**
     * Retourne le service Contrat.
     *
     * @return ContratService
     */
    public function getContratService()
    {
        return $this->serviceContrat;
    }
}