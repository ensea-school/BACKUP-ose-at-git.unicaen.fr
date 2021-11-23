<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeIntervention;

/**
 * Description of TypeInterventionAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionAwareTrait
{
    /**
     * @var TypeIntervention
     */
    private $typeIntervention;





    /**
     * @param TypeIntervention $typeIntervention
     * @return self
     */
    public function setTypeIntervention( TypeIntervention $typeIntervention = null )
    {
        $this->typeIntervention = $typeIntervention;
        return $this;
    }



    /**
     * @return TypeIntervention
     */
    public function getTypeIntervention()
    {
        return $this->typeIntervention;
    }
}