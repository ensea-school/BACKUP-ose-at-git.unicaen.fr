<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\MiseEnPaiementIntervenantStructure;

/**
 * Description of MiseEnPaiementIntervenantStructureAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementIntervenantStructureAwareTrait
{
    protected ?MiseEnPaiementIntervenantStructure $miseEnPaiementIntervenantStructure;



    /**
     * @param MiseEnPaiementIntervenantStructure|null $miseEnPaiementIntervenantStructure
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