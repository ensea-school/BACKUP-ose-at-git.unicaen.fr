<?php

namespace Intervenant\Entity\Db;

/**
 * Description of CiviliteAwareTrait
 *
 * @author UnicaenCode
 */
trait CiviliteAwareTrait
{
    protected ?Civilite $civilite = null;



    /**
     * @param Civilite $civilite
     *
     * @return self
     */
    public function setCivilite( ?Civilite $civilite )
    {
        $this->civilite = $civilite;

        return $this;
    }



    public function getCivilite(): ?Civilite
    {
        return $this->civilite;
    }
}