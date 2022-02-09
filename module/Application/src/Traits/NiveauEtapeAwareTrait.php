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
    protected ?NiveauEtape $niveauEtape = null;



    public function setNiveauEtape(?NiveauEtape $niveauEtape = null)
    {
        $this->niveauEtape = $niveauEtape;

        return $this;
    }



    public function getNiveauEtape(): ?NiveauEtape
    {
        return $this->niveauEtape;
    }
}