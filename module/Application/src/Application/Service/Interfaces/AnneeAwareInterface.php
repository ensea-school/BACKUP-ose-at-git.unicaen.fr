<?php

namespace Application\Service\Interfaces;

use Application\Service\AnneeService;
use RuntimeException;

/**
 * Description of AnneeAwareInterface
 *
 * @author UnicaenCode
 */
interface AnneeAwareInterface
{
    /**
     * @param AnneeService $serviceAnnee
     *
     * @return self
     */
    public function setServiceAnnee(AnneeService $serviceAnnee );



    /**
     * @return AnneeAwareInterface
     * @throws RuntimeException
     */
    public function getServiceAnnee();
}