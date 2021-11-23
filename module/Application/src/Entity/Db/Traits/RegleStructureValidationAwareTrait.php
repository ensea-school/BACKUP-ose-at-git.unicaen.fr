<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\RegleStructureValidation;

/**
 * Description of RegleStructureValidationAwareTrait
 *
 * @author UnicaenCode
 */
trait RegleStructureValidationAwareTrait
{
    /**
     * @var RegleStructureValidation
     */
    private $regleStructureValidation;





    /**
     * @param RegleStructureValidation $regleStructureValidation
     * @return self
     */
    public function setRegleStructureValidation( RegleStructureValidation $regleStructureValidation = null )
    {
        $this->regleStructureValidation = $regleStructureValidation;
        return $this;
    }



    /**
     * @return RegleStructureValidation
     */
    public function getRegleStructureValidation()
    {
        return $this->regleStructureValidation;
    }
}