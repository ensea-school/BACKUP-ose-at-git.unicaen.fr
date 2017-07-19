<?php

namespace Application\Service\Interfaces;

use Application\Service\Contrat;
use RuntimeException;

/**
 * Description of ContratAwareInterface
 *
 * @author UnicaenCode
 */
interface ContratAwareInterface
{
    /**
     * @param Contrat $serviceContrat
     * @return self
     */
    public function setServiceContrat( Contrat $serviceContrat );



    /**
     * @return ContratAwareInterface
     * @throws RuntimeException
     */
    public function getServiceContrat();
}