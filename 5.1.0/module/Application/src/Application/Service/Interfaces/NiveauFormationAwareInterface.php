<?php

namespace Application\Service\Interfaces;

use Application\Service\NiveauFormation;
use RuntimeException;

/**
 * Description of NiveauFormationAwareInterface
 *
 * @author UnicaenCode
 */
interface NiveauFormationAwareInterface
{
    /**
     * @param NiveauFormation $serviceNiveauFormation
     * @return self
     */
    public function setServiceNiveauFormation( NiveauFormation $serviceNiveauFormation );



    /**
     * @return NiveauFormationAwareInterface
     * @throws RuntimeException
     */
    public function getServiceNiveauFormation();
}