<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\MiseEnPaiement;

/**
 * Description of MiseEnPaiementAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementAwareTrait
{
    protected ?MiseEnPaiement $miseEnPaiement = null;



    /**
     * @param MiseEnPaiement $miseEnPaiement
     *
     * @return self
     */
    public function setMiseEnPaiement( MiseEnPaiement $miseEnPaiement )
    {
        $this->miseEnPaiement = $miseEnPaiement;

        return $this;
    }



    public function getMiseEnPaiement(): ?MiseEnPaiement
    {
        return $this->miseEnPaiement;
    }
}