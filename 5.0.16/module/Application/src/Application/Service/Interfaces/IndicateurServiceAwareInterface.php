<?php

namespace Application\Service\Interfaces;

use Application\Service\IndicateurService;
use RuntimeException;

/**
 * Description of IndicateurServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface IndicateurServiceAwareInterface
{
    /**
     * @param IndicateurService $serviceIndicateur
     * @return self
     */
    public function setServiceIndicateur( IndicateurService $serviceIndicateur );



    /**
     * @return IndicateurServiceAwareInterface
     * @throws RuntimeException
     */
    public function getServiceIndicateur();
}