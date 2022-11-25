<?php

namespace Service\Entity\Db;

/**
 * Description of RegleStructureValidationAwareTrait
 *
 * @author UnicaenCode
 */
trait RegleStructureValidationAwareTrait
{
    protected ?RegleStructureValidation $regleStructureValidation = null;



    /**
     * @param RegleStructureValidation $regleStructureValidation
     *
     * @return self
     */
    public function setRegleStructureValidation(?RegleStructureValidation $regleStructureValidation)
    {
        $this->regleStructureValidation = $regleStructureValidation;

        return $this;
    }



    public function getRegleStructureValidation(): ?RegleStructureValidation
    {
        return $this->regleStructureValidation;
    }
}