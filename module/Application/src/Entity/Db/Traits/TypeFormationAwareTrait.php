<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeFormation;

/**
 * Description of TypeFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeFormationAwareTrait
{
    protected ?TypeFormation $typeFormation = null;



    /**
     * @param TypeFormation $typeFormation
     *
     * @return self
     */
    public function setTypeFormation( TypeFormation $typeFormation )
    {
        $this->typeFormation = $typeFormation;

        return $this;
    }



    public function getTypeFormation(): ?TypeFormation
    {
        return $this->typeFormation;
    }
}