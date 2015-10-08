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
    /**
     * @var TypeFormation
     */
    private $typeFormation;





    /**
     * @param TypeFormation $typeFormation
     * @return self
     */
    public function setTypeFormation( TypeFormation $typeFormation = null )
    {
        $this->typeFormation = $typeFormation;
        return $this;
    }



    /**
     * @return TypeFormation
     */
    public function getTypeFormation()
    {
        return $this->typeFormation;
    }
}