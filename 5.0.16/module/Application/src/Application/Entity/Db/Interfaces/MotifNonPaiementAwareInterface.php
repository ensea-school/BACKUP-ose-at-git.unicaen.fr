<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\MotifNonPaiement;

/**
 * Description of MotifNonPaiementAwareInterface
 *
 * @author UnicaenCode
 */
interface MotifNonPaiementAwareInterface
{
    /**
     * @param MotifNonPaiement $motifNonPaiement
     * @return self
     */
    public function setMotifNonPaiement( MotifNonPaiement $motifNonPaiement = null );



    /**
     * @return MotifNonPaiement
     */
    public function getMotifNonPaiement();
}