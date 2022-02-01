<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\MotifNonPaiement;

/**
 * Description of MotifNonPaiementAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifNonPaiementAwareTrait
{
    protected ?MotifNonPaiement $motifNonPaiement;



    /**
     * @param MotifNonPaiement|null $motifNonPaiement
     *
     * @return self
     */
    public function setMotifNonPaiement( ?MotifNonPaiement $motifNonPaiement )
    {
        $this->motifNonPaiement = $motifNonPaiement;

        return $this;
    }



    public function getMotifNonPaiement(): ?MotifNonPaiement
    {
        return $this->motifNonPaiement;
    }
}