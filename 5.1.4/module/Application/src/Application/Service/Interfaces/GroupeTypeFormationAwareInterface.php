<?php

namespace Application\Service\Interfaces;

use Application\Service\GroupeTypeFormation;
use RuntimeException;

/**
 * Description of GroupeTypeFormationAwareInterface
 *
 * @author UnicaenCode
 */
interface GroupeTypeFormationAwareInterface
{
    /**
     * @param GroupeTypeFormation $serviceGroupeTypeFormation
     * @return self
     */
    public function setServiceGroupeTypeFormation( GroupeTypeFormation $serviceGroupeTypeFormation );



    /**
     * @return GroupeTypeFormationAwareInterface
     * @throws RuntimeException
     */
    public function getServiceGroupeTypeFormation();
}