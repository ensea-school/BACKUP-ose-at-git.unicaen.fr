<?php

namespace Application\Traits;

use Application\Entity\Db\TypeContrat;

/**
 * Description of TypeContratAwareTrait
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait TypeContratAwareTrait
{
    /**
     * @var TypeContrat 
     */
    protected $typeContrat;
    
    /**
     * Spécifie le type de contrat concerné.
     * 
     * @param TypeContrat $typeContrat type de contrat concerné
     */
    public function setTypeContrat(TypeContrat $typeContrat)
    {
        $this->typeContrat = $typeContrat;
        
        return $this;
    }
    
    /**
     * Retourne le type de contrat concerné.
     * 
     * @return TypeContrat
     */
    public function getTypeContrat()
    {
        return $this->typeContrat;
    }
}