<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\CentreCout;

/**
 * Description of CentreCoutAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutAwareTrait
{
    protected ?CentreCout $centreCout;



    /**
     * @param CentreCout|null $centreCout
     *
     * @return self
     */
    public function setCentreCout( ?CentreCout $centreCout )
    {
        $this->centreCout = $centreCout;

        return $this;
    }



    public function getCentreCout(): ?CentreCout
    {
        return $this->centreCout;
    }
}