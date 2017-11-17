<?php

namespace Application\Service\Interfaces;

use Application\Service\AffectationService;
use RuntimeException;

/**
 * Description of AffectationAwareInterface
 *
 * @author UnicaenCode
 */
interface AffectationAwareInterface
{
    /**
     * @param AffectationService $serviceAffectation
     *
     * @return self
     */
    public function setServiceAffectation(AffectationService $serviceAffectation );



    /**
     * @return AffectationAwareInterface
     * @throws RuntimeException
     */
    public function getServiceAffectation();
}