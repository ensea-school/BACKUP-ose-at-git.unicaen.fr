<?php

namespace OffreFormation\Entity\Db\Traits;

use OffreFormation\Entity\Db\CentreCoutEp;

/**
 * Description of CentreCoutEpAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutEpAwareTrait
{
    protected ?CentreCoutEp $centreCoutEp = null;



    /**
     * @param CentreCoutEp $centreCoutEp
     *
     * @return self
     */
    public function setCentreCoutEp( ?CentreCoutEp $centreCoutEp )
    {
        $this->centreCoutEp = $centreCoutEp;

        return $this;
    }



    public function getCentreCoutEp(): ?CentreCoutEp
    {
        return $this->centreCoutEp;
    }
}