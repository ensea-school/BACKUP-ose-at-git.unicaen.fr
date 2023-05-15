<?php

namespace Paiement\Entity\Db;

/**
 * Description of MotifNonPaiementAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifNonPaiementAwareTrait
{
    protected ?MotifNonPaiement $motifNonPaiement = null;



    /**
     * @param MotifNonPaiement $motifNonPaiement
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