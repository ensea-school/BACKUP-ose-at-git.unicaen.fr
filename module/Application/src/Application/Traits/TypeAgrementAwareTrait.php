<?php

namespace Application\Traits;

use Application\Entity\Db\TypeAgrement;

/**
 * Description of TypeAgrementAwareTrait
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait TypeAgrementAwareTrait
{
    /**
     * @var TypeAgrement 
     */
    protected $typeAgrement;
    
    /**
     * Spécifie le type d'agrément concerné.
     * 
     * @param TypeAgrement $typeAgrement type d'agrément concerné
     */
    public function setTypeAgrement(TypeAgrement $typeAgrement)
    {
        if (!in_array($typeAgrement->getCode(), [ TypeAgrement::CODE_CONSEIL_RESTREINT, TypeAgrement::CODE_CONSEIL_ACADEMIQUE ])) {
            throw new \Common\Exception\LogicException("Type d'agrément spécifié inattendu!");
        }
        
        $this->typeAgrement = $typeAgrement;
        
        return $this;
    }
    
    /**
     * Retourne le type d'agrément concerné.
     * 
     * @return TypeAgrement
     */
    public function getTypeAgrement()
    {
        return $this->typeAgrement;
    }
}