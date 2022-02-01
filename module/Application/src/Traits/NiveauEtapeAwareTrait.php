<?php

namespace Application\Traits;

use Application\Entity\NiveauEtape;

/**
 * Description of NiveauEtapeAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait NiveauEtapeAwareTrait
{
    /**
     * @var NiveauEtape
     */
    protected $niveauEtape;



    /**
     * Spécifie le niveau d'étape concerné.
     *
     * @param NiveauEtape $niveauEtape le niveau d'étape concerné
     */
    public function setNiveauEtape(NiveauEtape $niveauEtape = null)
    {
        $this->niveauEtape = $niveauEtape;

        return $this;
    }



    /**
     * Retourne le niveau d'étape concerné.
     *
     * @return NiveauEtape
     */
    public function getNiveauEtape(): NiveauEtape
    {
        return $this->niveauEtape;
    }
}