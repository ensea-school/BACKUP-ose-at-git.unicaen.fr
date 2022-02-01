<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeCentreCout;

/**
 * Description of TypeCentreCoutAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeCentreCoutAwareTrait
{
    protected ?TypeCentreCout $typeCentreCout = null;



    /**
     * @param TypeCentreCout $typeCentreCout
     *
     * @return self
     */
    public function setTypeCentreCout( ?TypeCentreCout $typeCentreCout )
    {
        $this->typeCentreCout = $typeCentreCout;

        return $this;
    }



    public function getTypeCentreCout(): ?TypeCentreCout
    {
        return $this->typeCentreCout;
    }
}