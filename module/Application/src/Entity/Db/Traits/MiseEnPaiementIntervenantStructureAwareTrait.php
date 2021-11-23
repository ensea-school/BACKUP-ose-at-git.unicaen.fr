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
    /**
     * @var MiseEnPaiementIntervenantStructure
     */
    private $miseEnPaiementIntervenantStructure;





    /**
     * @param MiseEnPaiementIntervenantStructure $miseEnPaiementIntervenantStructure
     * @return self
     */
    public function setMiseEnPaiementIntervenantStructure( MiseEnPaiementIntervenantStructure $miseEnPaiementIntervenantStructure = null )
    {
        $this->miseEnPaiementIntervenantStructure = $miseEnPaiementIntervenantStructure;
        return $this;
    }



    /**
     * @return MiseEnPaiementIntervenantStructure
     */
    public function getMiseEnPaiementIntervenantStructure()
    {
        return $this->miseEnPaiementIntervenantStructure;
    }
}