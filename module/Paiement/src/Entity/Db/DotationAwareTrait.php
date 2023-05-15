<?php

namespace Paiement\Entity\Db;

/**
 * Description of DotationAwareTrait
 *
 * @author UnicaenCode
 */
trait DotationAwareTrait
{
    protected ?Dotation $dotation = null;



    /**
     * @param Dotation $dotation
     *
     * @return self
     */
    public function setDotation( ?Dotation $dotation )
    {
        $this->dotation = $dotation;

        return $this;
    }



    public function getDotation(): ?Dotation
    {
        return $this->dotation;
    }
}