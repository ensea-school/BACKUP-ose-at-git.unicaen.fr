<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\MiseEnPaiementIntervenantStructure;

/**
 * Description of MiseEnPaiementIntervenantStructureAwareInterface
 *
 * @author UnicaenCode
 */
interface MiseEnPaiementIntervenantStructureAwareInterface
{
    /**
     * @param MiseEnPaiementIntervenantStructure $miseEnPaiementIntervenantStructure
     * @return self
     */
    public function setMiseEnPaiementIntervenantStructure( MiseEnPaiementIntervenantStructure $miseEnPaiementIntervenantStructure = null );



    /**
     * @return MiseEnPaiementIntervenantStructure
     */
    public function getMiseEnPaiementIntervenantStructure();
}