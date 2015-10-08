<?php

namespace Application\Service\Interfaces;

use Application\Service\NiveauEtape;
use RuntimeException;

/**
 * Description of NiveauEtapeAwareInterface
 *
 * @author UnicaenCode
 */
interface NiveauEtapeAwareInterface
{
    /**
     * @param NiveauEtape $serviceNiveauEtape
     * @return self
     */
    public function setServiceNiveauEtape( NiveauEtape $serviceNiveauEtape );



    /**
     * @return NiveauEtapeAwareInterface
     * @throws RuntimeException
     */
    public function getServiceNiveauEtape();
}