<?php

namespace Application\Traits;

use Application\Entity\Db\Intervenant;

/**
 * Description of IntervenantAwareTrait
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait IntervenantAwareTrait
{
    /**
     * @var Intervenant 
     */
    protected $intervenant;
    
    /**
     * Spécifie l'intervenant concerné.
     * 
     * @param Intervenant $intervenant Intervenant concerné
     * @return self
     */
    public function setIntervenant(Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;
        
        return $this;
    }
    
    /**
     * Retourne l'intervenant concerné.
     * 
     * @return Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }
}