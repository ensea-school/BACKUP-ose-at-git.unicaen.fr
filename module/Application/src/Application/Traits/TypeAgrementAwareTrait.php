<?php

namespace Application\Traits;

use Application\Entity\Db\TypeAgrement;
use Common\Exception\LogicException;

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
    public function setTypeAgrement(TypeAgrement $typeAgrement = null)
    {
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