<?php

namespace Application\Service\Interfaces;

use Application\Service\Perimetre;
use RuntimeException;

/**
 * Description of PerimetreAwareInterface
 *
 * @author UnicaenCode
 */
interface PerimetreAwareInterface
{
    /**
     * @param Perimetre $servicePerimetre
     * @return self
     */
    public function setServicePerimetre( Perimetre $servicePerimetre );



    /**
     * @return PerimetreAwareInterface
     * @throws RuntimeException
     */
    public function getServicePerimetre();
}