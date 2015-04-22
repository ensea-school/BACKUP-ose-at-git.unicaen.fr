<?php

namespace Application\Traits;

use Application\Entity\Db\Annee;

/**
 * Description of AnneeAwareTrait
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait AnneeAwareTrait
{
    /**
     * @var Annee 
     */
    protected $annee;
    
    /**
     * Spécifie l'annee concerné.
     * 
     * @param Annee $annee Annee concerné
     */
    public function setAnnee(Annee $annee = null)
    {
        $this->annee = $annee;
        
        return $this;
    }
    
    /**
     * Retourne l'annee concerné.
     * 
     * @return Annee
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}