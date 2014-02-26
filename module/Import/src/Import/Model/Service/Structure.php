<?php

namespace Import\Model\Service;

use Import\Model\Entity\Structure as StructureEntity;
use Import\Model\Entity\Structure\Etablissement as EtablissementEntity;

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

    /**
     * Retourne la liste des identifiants d'établissements
     *
     * @return string[]
     */
    public function getEtablissementList()
    {
        return $this->getMapper()->getEtablissementList();
    }

    /**
     * Retourne les données d'un établissement
     *
     * @param string $id
     * @return EtablissementEntity
     */
    public function getEtablissement( $id )
    {
        return $this->getMapper()->getEtablissement( $id );
    }
}