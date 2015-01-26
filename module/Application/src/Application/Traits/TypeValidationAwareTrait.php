<?php

namespace Application\Traits;

use Application\Entity\Db\TypeValidation;

/**
 * Description of TypeValidationAwareTrait
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait TypeValidationAwareTrait
{
    /**
     * @var TypeValidation 
     */
    protected $typeValidation;
    
    /**
     * Spécifie le type de validation concerné.
     * 
     * @param TypeValidation $typeValidation type de validation concerné
     */
    public function setTypeValidation(TypeValidation $typeValidation = null)
    {
        $this->typeValidation = $typeValidation;
        
        return $this;
    }
    
    /**
     * Retourne le type de validation concerné.
     * 
     * @return TypeValidation
     */
    public function getTypeValidation()
    {
        return $this->typeValidation;
    }
}