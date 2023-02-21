<?php

namespace OffreFormation\Entity\Db\Traits;

use OffreFormation\Entity\Db\NiveauFormation;

/**
 * Description of NiveauFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait NiveauFormationAwareTrait
{
    protected ?NiveauFormation $niveauFormation = null;



    /**
     * @param NiveauFormation $niveauFormation
     *
     * @return self
     */
    public function setNiveauFormation( ?NiveauFormation $niveauFormation )
    {
        $this->niveauFormation = $niveauFormation;

        return $this;
    }



    public function getNiveauFormation(): ?NiveauFormation
    {
        return $this->niveauFormation;
    }
}