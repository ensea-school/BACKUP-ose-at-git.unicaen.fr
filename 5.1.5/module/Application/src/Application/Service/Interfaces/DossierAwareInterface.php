<?php

namespace Application\Service\Interfaces;

use Application\Service\Dossier;
use RuntimeException;

/**
 * Description of DossierAwareInterface
 *
 * @author UnicaenCode
 */
interface DossierAwareInterface
{
    /**
     * @param Dossier $serviceDossier
     * @return self
     */
    public function setServiceDossier( Dossier $serviceDossier );



    /**
     * @return DossierAwareInterface
     * @throws RuntimeException
     */
    public function getServiceDossier();
}