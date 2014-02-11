<?php

namespace Import\Model\Service;

use Import\Model\Entity\Structure as StructureEntity;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Structure extends Service {

    /**
     * Retourne la liste des identifiants de structures
     * 
     * @return string[]
     */
    public function getList()
    {
        return $this->getMapper()->getStructureList();
    }

    /**
     * Retourne les données d'une structure
     *
     * @param string $id
     * @return StructureEntity
     */
    public function get( $id )
    {
        return $this->getMapper()->getStructure( $id );
    }
}