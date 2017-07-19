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
    /**
     * @var MotifNonPaiement
     */
    private $motifNonPaiement;





    /**
     * @param MotifNonPaiement $motifNonPaiement
     * @return self
     */
    public function setMotifNonPaiement( MotifNonPaiement $motifNonPaiement = null )
    {
        $this->motifNonPaiement = $motifNonPaiement;
        return $this;
    }



    /**
     * @return MotifNonPaiement
     */
    public function getMotifNonPaiement()
    {
        return $this->motifNonPaiement;
    }
}