<?php

namespace Paiement\Entity\Db;

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