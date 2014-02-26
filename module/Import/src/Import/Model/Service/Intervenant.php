<?php

namespace Import\Model\Service;

use Import\Model\Entity\Intervenant\Intervenant as IntervenantEntity;
use Import\Model\Entity\Intervenant\Permanent as IntervenantPermanentEntity;
use Import\Model\Entity\Intervenant\Exterieur as IntervenantExterieurEntity;
use Import\Model\Entity\Intervenant\Adresse as IntervenantAdresseEntity;

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
     * @return IntervenantEntity
     */
    public function get( $id )
    {
        return $this->getMapper()->getIntervenant( $id );
    }

    /**
     * Retourne les données d'un intervenant permanent
     *
     * @param string $id
     * @return IntervenantPermanentEntity
     */
    public function getPermanent( $id )
    {
        return $this->getMapper()->getIntervenantPermanent( $id );
    }

    /**
     * Retourne les données d'un intervenant exterieur
     *
     * @param string $id
     * @return IntervenantExterieurEntity
     */
    public function getExterieur( $id )
    {
        return $this->getMapper()->getIntervenantExterieur( $id );
    }

    /**
     * Retourne la liste des adresses d'un intervenant
     *
     * @param string $id Identifiant de l'intervenant
     * @return IntervenantAdresseEntity[]
     * @throws Exception
     */
    public function getAdresses( $id )
    {
        return $this->getMapper()->getIntervenantAdresses( $id );
    }
}