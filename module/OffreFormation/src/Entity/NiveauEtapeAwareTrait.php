<?php

namespace OffreFormation\Entity;

/**
 * Description of NiveauEtapeAwareTrait
 *
 * @author UnicaenCode
 */
trait NiveauEtapeAwareTrait
{
    protected ?NiveauEtape $niveauEtape = null;



    /**
     * @param NiveauEtape $niveauEtape
     *
     * @return self
     */
    public function setNiveauEtape( ?NiveauEtape $niveauEtape )
    {
        $this->niveauEtape = $niveauEtape;

        return $this;
    }



    public function getNiveauEtape(): ?NiveauEtape
    {
        return $this->niveauEtape;
    }
}