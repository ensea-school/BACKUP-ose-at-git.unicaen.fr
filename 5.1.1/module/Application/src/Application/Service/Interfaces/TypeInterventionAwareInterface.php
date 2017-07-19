<?php

namespace Application\Service\Interfaces;

use Application\Service\TypeIntervention;
use RuntimeException;

/**
 * Description of TypeInterventionAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeInterventionAwareInterface
{
    /**
     * @param TypeIntervention $serviceTypeIntervention
     * @return self
     */
    public function setServiceTypeIntervention( TypeIntervention $serviceTypeIntervention );



    /**
     * @return TypeInterventionAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypeIntervention();
}