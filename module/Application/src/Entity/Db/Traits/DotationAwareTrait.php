<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Dotation;

/**
 * Description of DotationAwareTrait
 *
 * @author UnicaenCode
 */
trait DotationAwareTrait
{
    protected ?Dotation $dotation;



    /**
     * @param Dotation|null $dotation
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