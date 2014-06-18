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
     * Spécifie l'année concernée.
     * 
     * @param Annee $annee Annee concerné
     */
    public function setAnnee(Annee $annee = null)
    {
        $this->annee = $annee;
    }
    
    /**
     * Retourne l'année concernée.
     * 
     * @return Annee
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}