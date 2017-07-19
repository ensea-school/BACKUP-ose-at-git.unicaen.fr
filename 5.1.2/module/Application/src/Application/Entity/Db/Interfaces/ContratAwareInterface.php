<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Contrat;

/**
 * Description of ContratAwareInterface
 *
 * @author UnicaenCode
 */
interface ContratAwareInterface
{
    /**
     * @param Contrat $contrat
     * @return self
     */
    public function setContrat( Contrat $contrat = null );



    /**
     * @return Contrat
     */
    public function getContrat();
}