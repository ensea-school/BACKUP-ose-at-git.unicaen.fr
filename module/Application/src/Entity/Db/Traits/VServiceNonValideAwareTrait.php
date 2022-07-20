<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VServiceNonValide;

/**
 * Description of VServiceNonValideAwareTrait
 *
 * @author UnicaenCode
 */
trait VServiceNonValideAwareTrait
{
    protected ?VServiceNonValide $vServiceNonValide = null;



    /**
     * @param VServiceNonValide $vServiceNonValide
     *
     * @return self
     */
    public function setVServiceNonValide( ?VServiceNonValide $vServiceNonValide )
    {
        $this->vServiceNonValide = $vServiceNonValide;

        return $this;
    }



    public function getVServiceNonValide(): ?VServiceNonValide
    {
        return $this->vServiceNonValide;
    }
}