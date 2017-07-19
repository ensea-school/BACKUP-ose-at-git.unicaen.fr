<?php

namespace Application\Service\Interfaces;

use Application\Service\Parametres;
use RuntimeException;

/**
 * Description of ParametresAwareInterface
 *
 * @author UnicaenCode
 */
interface ParametresAwareInterface
{
    /**
     * @param Parametres $serviceParametres
     * @return self
     */
    public function setServiceParametres( Parametres $serviceParametres );



    /**
     * @return ParametresAwareInterface
     * @throws RuntimeException
     */
    public function getServiceParametres();
}