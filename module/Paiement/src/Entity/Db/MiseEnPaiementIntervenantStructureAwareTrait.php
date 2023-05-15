<?php

namespace Paiement\Entity\Db;

/**
 * Description of MiseEnPaiementIntervenantStructureAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementIntervenantStructureAwareTrait
{
    protected ?MiseEnPaiementIntervenantStructure $miseEnPaiementIntervenantStructure = null;



    /**
     * @param MiseEnPaiementIntervenantStructure $miseEnPaiementIntervenantStructure
     *
     * @return self
     */
    public function setMiseEnPaiementIntervenantStructure( ?MiseEnPaiementIntervenantStructure $miseEnPaiementIntervenantStructure )
    {
        $this->miseEnPaiementIntervenantStructure = $miseEnPaiementIntervenantStructure;

        return $this;
    }



    public function getMiseEnPaiementIntervenantStructure(): ?MiseEnPaiementIntervenantStructure
    {
        return $this->miseEnPaiementIntervenantStructure;
    }
}