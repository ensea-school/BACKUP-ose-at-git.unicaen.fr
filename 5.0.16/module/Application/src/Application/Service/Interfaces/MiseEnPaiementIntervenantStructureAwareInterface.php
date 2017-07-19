<?php

namespace Application\Service\Interfaces;

use Application\Service\MiseEnPaiementIntervenantStructure;
use RuntimeException;

/**
 * Description of MiseEnPaiementIntervenantStructureAwareInterface
 *
 * @author UnicaenCode
 */
interface MiseEnPaiementIntervenantStructureAwareInterface
{
    /**
     * @param MiseEnPaiementIntervenantStructure $serviceMiseEnPaiementIntervenantStructure
     * @return self
     */
    public function setServiceMiseEnPaiementIntervenantStructure( MiseEnPaiementIntervenantStructure $serviceMiseEnPaiementIntervenantStructure );



    /**
     * @return MiseEnPaiementIntervenantStructureAwareInterface
     * @throws RuntimeException
     */
    public function getServiceMiseEnPaiementIntervenantStructure();
}