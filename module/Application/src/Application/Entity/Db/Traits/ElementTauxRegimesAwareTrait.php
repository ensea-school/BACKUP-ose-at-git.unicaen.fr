<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\ElementTauxRegimes;

/**
 * Description of ElementTauxRegimesAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementTauxRegimesAwareTrait
{
    /**
     * @var ElementTauxRegimes
     */
    protected $elementTauxRegimes;





    /**
     * @param ElementTauxRegimes $elementTauxRegimes
     * @return self
     */
    public function setElementTauxRegimes( ElementTauxRegimes $elementTauxRegimes = null )
    {
        $this->elementTauxRegimes = $elementTauxRegimes;
        return $this;
    }



    /**
     * @return ElementTauxRegimes
     */
    public function getElementTauxRegimes()
    {
        return $this->elementTauxRegimes;
    }
}