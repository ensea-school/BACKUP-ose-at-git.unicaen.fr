<?php

namespace Application\Service\Interfaces;

use Application\Service\Indicateur;
use RuntimeException;

/**
 * Description of IndicateurAwareInterface
 *
 * @author UnicaenCode
 */
interface IndicateurAwareInterface
{
    /**
     * @param Indicateur $serviceIndicateur
     * @return self
     */
    public function setServiceIndicateur( Indicateur $serviceIndicateur );



    /**
     * @return IndicateurAwareInterface
     * @throws RuntimeException
     */
    public function getServiceIndicateur();
}