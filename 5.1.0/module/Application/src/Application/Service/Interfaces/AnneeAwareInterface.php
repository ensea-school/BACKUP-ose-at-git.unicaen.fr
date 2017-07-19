<?php

namespace Application\Service\Interfaces;

use Application\Service\Annee;
use RuntimeException;

/**
 * Description of AnneeAwareInterface
 *
 * @author UnicaenCode
 */
interface AnneeAwareInterface
{
    /**
     * @param Annee $serviceAnnee
     * @return self
     */
    public function setServiceAnnee( Annee $serviceAnnee );



    /**
     * @return AnneeAwareInterface
     * @throws RuntimeException
     */
    public function getServiceAnnee();
}