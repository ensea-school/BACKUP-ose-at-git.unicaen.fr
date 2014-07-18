<?php

namespace Application\Traits;

use Application\Entity\Db\Contrat;

/**
 * Description of ContratAwareTrait
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait ContratAwareTrait
{
    /**
     * @var Contrat 
     */
    protected $contrat;
    
    /**
     * Spécifie l'contrat concerné.
     * 
     * @param Contrat $contrat Contrat concerné
     * @return self
     */
    public function setContrat(Contrat $contrat)
    {
        $this->contrat = $contrat;
        
        return $this;
    }
    
    /**
     * Retourne l'contrat concerné.
     * 
     * @return Contrat
     */
    public function getContrat()
    {
        return $this->contrat;
    }
}