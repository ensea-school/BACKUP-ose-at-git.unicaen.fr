<?php

namespace Application\Interfaces;

use Application\Entity\Db\Structure;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface StructureAwareInterface
{
    
    /**
     * Spécifie la structure concernée.
     * 
     * @param Structure $structure Structure concernée
     */
    public function setStructure(Structure $structure = null);
    
    /**
     * Retourne la structure concernée.
     * 
     * @return Structure
     */
    public function getStructure();
}