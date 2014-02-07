<?php

namespace Import\Model\Service;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Intervenant extends Service {

    /**
     * recherche un ensemble d'enseignants
     *
     * @param string $term
     * @return array[]
     */
    public function search( $term )
    {
        return $this->getMapper()->searchIntervenant( $term );
    }

    /**
     * Retourne les données d'un intervenant
     *
     * @param string $id
     * @return array
     */
    public function get( $id )
    {
        return $this->getMapper()->getIntervenant( $id );
    }

    /**
     * Retourne la liste des adresses d'un intervenant
     *
     * @param string $id Identifiant de l'intervenant
     * @return \Import\Model\Entity\Intervenant\Adresse[]
     * @throws Exception
     */
    public function getAdresses( $id )
    {
        return $this->getMapper()->getIntervenantAdresses( $id );
    }
}