<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeIntervention;

/**
 * Description of TypeInterventionAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeInterventionAwareInterface
{
    /**
     * @param TypeIntervention $typeIntervention
     * @return self
     */
    public function setTypeIntervention( TypeIntervention $typeIntervention = null );



    /**
     * @return TypeIntervention
     */
    public function getTypeIntervention();
}