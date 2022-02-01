<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\CentreCoutEp;

/**
 * Description of CentreCoutEpAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutEpAwareTrait
{
    protected ?CentreCoutEp $centreCoutEp;



    /**
     * @param CentreCoutEp|null $centreCoutEp
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