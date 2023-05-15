<?php

namespace Paiement\Entity\Db;

/**
 * Description of CentreCoutAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutAwareTrait
{
    protected ?CentreCout $centreCout = null;



    /**
     * @param CentreCout $centreCout
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