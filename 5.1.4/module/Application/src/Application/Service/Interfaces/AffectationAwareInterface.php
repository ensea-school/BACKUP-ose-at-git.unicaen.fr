<?php

namespace Application\Service\Interfaces;

use Application\Service\Affectation;
use RuntimeException;

/**
 * Description of AffectationAwareInterface
 *
 * @author UnicaenCode
 */
interface AffectationAwareInterface
{
    /**
     * @param Affectation $serviceAffectation
     * @return self
     */
    public function setServiceAffectation( Affectation $serviceAffectation );



    /**
     * @return AffectationAwareInterface
     * @throws RuntimeException
     */
    public function getServiceAffectation();
}