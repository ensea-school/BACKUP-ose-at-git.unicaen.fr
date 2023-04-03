<?php

namespace OffreFormation\Entity\Db\Traits;

use OffreFormation\Entity\Db\ElementTauxRegimes;

/**
 * Description of ElementTauxRegimesAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementTauxRegimesAwareTrait
{
    protected ?ElementTauxRegimes $elementTauxRegimes = null;



    /**
     * @param ElementTauxRegimes $elementTauxRegimes
     *
     * @return self
     */
    public function setElementTauxRegimes( ?ElementTauxRegimes $elementTauxRegimes )
    {
        $this->elementTauxRegimes = $elementTauxRegimes;

        return $this;
    }



    public function getElementTauxRegimes(): ?ElementTauxRegimes
    {
        return $this->elementTauxRegimes;
    }
}