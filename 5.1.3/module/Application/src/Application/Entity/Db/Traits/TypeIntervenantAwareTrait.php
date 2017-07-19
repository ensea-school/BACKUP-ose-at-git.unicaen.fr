<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeIntervenant;

/**
 * Description of TypeIntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeIntervenantAwareTrait
{
    /**
     * @var TypeIntervenant
     */
    private $typeIntervenant;





    /**
     * @param TypeIntervenant $typeIntervenant
     * @return self
     */
    public function setTypeIntervenant( TypeIntervenant $typeIntervenant = null )
    {
        $this->typeIntervenant = $typeIntervenant;
        return $this;
    }



    /**
     * @return TypeIntervenant
     */
    public function getTypeIntervenant()
    {
        return $this->typeIntervenant;
    }
}