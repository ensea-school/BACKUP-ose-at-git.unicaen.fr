<?php

namespace Application\Interfaces;

use Application\Entity\Db\Intervenant;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface IntervenantAwareInterface
{
    
    /**
     * Spécifie l'intervenant concerné.
     * 
     * @param Intervenant $intervenant Intervenant concerné
     * @return self
     */
    public function setIntervenant(Intervenant $intervenant = null);
    
    /**
     * Retourne l'intervenant concerné.
     * 
     * @return Intervenant
     */
    public function getIntervenant();
}