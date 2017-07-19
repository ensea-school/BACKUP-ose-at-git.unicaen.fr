<?php

namespace Application\Service\Interfaces;

use Application\Service\StatutIntervenant;
use RuntimeException;

/**
 * Description of StatutIntervenantAwareInterface
 *
 * @author UnicaenCode
 */
interface StatutIntervenantAwareInterface
{
    /**
     * @param StatutIntervenant $serviceStatutIntervenant
     * @return self
     */
    public function setServiceStatutIntervenant( StatutIntervenant $serviceStatutIntervenant );



    /**
     * @return StatutIntervenantAwareInterface
     * @throws RuntimeException
     */
    public function getServiceStatutIntervenant();
}