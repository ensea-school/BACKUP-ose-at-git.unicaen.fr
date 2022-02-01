<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\CcActivite;

/**
 * Description of CcActiviteAwareTrait
 *
 * @author UnicaenCode
 */
trait CcActiviteAwareTrait
{
    protected ?CcActivite $ccActivite = null;



    /**
     * @param CcActivite $ccActivite
     *
     * @return self
     */
    public function setCcActivite( ?CcActivite $ccActivite )
    {
        $this->ccActivite = $ccActivite;

        return $this;
    }



    public function getCcActivite(): ?CcActivite
    {
        return $this->ccActivite;
    }
}