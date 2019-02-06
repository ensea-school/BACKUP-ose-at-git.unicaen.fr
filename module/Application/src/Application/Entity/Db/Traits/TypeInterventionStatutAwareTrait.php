<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeInterventionStatut;

/**
 * Description of TypeInterventionStatutAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStatutAwareTrait
{
    /**
     * @var TypeInterventionStatut
     */
    private $typeInterventionStatut;





    /**
     * @param TypeInterventionStatut $typeInterventionStatut
     * @return self
     */
    public function setTypeInterventionStatut( TypeInterventionStatut $typeInterventionStatut = null )
    {
        $this->typeInterventionStatut = $typeInterventionStatut;
        return $this;
    }



    /**
     * @return TypeInterventionStatut
     */
    public function getTypeInterventionStatut()
    {
        return $this->typeInterventionStatut;
    }
}