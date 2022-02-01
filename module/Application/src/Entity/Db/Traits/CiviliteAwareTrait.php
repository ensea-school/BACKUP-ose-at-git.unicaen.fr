<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Civilite;

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
    public function setCivilite( Civilite $civilite )
    {
        $this->civilite = $civilite;

        return $this;
    }



    public function getCivilite(): ?Civilite
    {
        return $this->civilite;
    }
}