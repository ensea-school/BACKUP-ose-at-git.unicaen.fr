<?php

namespace Application\Service\Interfaces;

use Application\Service\Intervenant;
use RuntimeException;

/**
 * Description of IntervenantAwareInterface
 *
 * @author UnicaenCode
 */
interface IntervenantAwareInterface
{
    /**
     * @param Intervenant $serviceIntervenant
     * @return self
     */
    public function setServiceIntervenant( Intervenant $serviceIntervenant );



    /**
     * @return IntervenantAwareInterface
     * @throws RuntimeException
     */
    public function getServiceIntervenant();
}