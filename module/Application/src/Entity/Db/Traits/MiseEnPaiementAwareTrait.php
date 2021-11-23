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
    /**
     * @var MiseEnPaiement
     */
    private $miseEnPaiement;





    /**
     * @param MiseEnPaiement $miseEnPaiement
     * @return self
     */
    public function setMiseEnPaiement( MiseEnPaiement $miseEnPaiement = null )
    {
        $this->miseEnPaiement = $miseEnPaiement;
        return $this;
    }



    /**
     * @return MiseEnPaiement
     */
    public function getMiseEnPaiement()
    {
        return $this->miseEnPaiement;
    }
}