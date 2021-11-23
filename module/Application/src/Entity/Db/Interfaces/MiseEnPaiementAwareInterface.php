<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\MiseEnPaiement;

/**
 * Description of MiseEnPaiementAwareInterface
 *
 * @author UnicaenCode
 */
interface MiseEnPaiementAwareInterface
{
    /**
     * @param MiseEnPaiement $miseEnPaiement
     * @return self
     */
    public function setMiseEnPaiement( MiseEnPaiement $miseEnPaiement = null );



    /**
     * @return MiseEnPaiement
     */
    public function getMiseEnPaiement();
}