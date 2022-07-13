<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VServiceValide;

/**
 * Description of VServiceValideAwareTrait
 *
 * @author UnicaenCode
 */
trait VServiceValideAwareTrait
{
    protected ?VServiceValide $vServiceValide = null;



    /**
     * @param VServiceValide $vServiceValide
     *
     * @return self
     */
    public function setVServiceValide( ?VServiceValide $vServiceValide )
    {
        $this->vServiceValide = $vServiceValide;

        return $this;
    }



    public function getVServiceValide(): ?VServiceValide
    {
        return $this->vServiceValide;
    }
}