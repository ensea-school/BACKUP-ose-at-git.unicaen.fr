<?php

namespace Import\Processus;

use Import\Entity\Differentiel\Query;


/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Import extends Processus
{

    /**
     * Mise à jour de l'existant uniquement
     */
    const A_UPDATE = 'update';

    /**
     * Insertion de nouvelles données ou restauration d'anciennes uniquement
     */
    const A_INSERT = 'insert';

    /**
     * Mise à jour globale
     */
    const A_ALL = 'all';

    /**
     * Retourne le générateur de requêtes
     *
     * @return \Import\Service\QueryGenerator
     */
    protected function getQueryGenerator()
    {
        return $this->getServiceManager()->get('importServiceQueryGenerator');
    }

    /**
     * Mise à jour des vues différentielles et des paquetages de mise à jour des données
     *
     * @return self
     */
    public function updateViewsAndPackages()
    {
        $this->getQueryGenerator()->updateViewsAndPackages();
        return $this;
    }

    /**
     * Import d'une ou plusieurs structures
     *
     * @param string|array|null $sourceCode  Identifiant source de la structure
     * @param string            $action      Action
     * @return self
     */
    public function structure( $sourceCode=null, $action=self::A_ALL )
    {
        $this->execMaj( 'STRUCTURE', 'SOURCE_CODE', $sourceCode, $action );

        $ids = $this->getQueryGenerator()->getIdFromSourceCode( 'STRUCTURE', $sourceCode );
        $this->execMaj( 'ADRESSE_STRUCTURE', 'STRUCTURE_ID', $ids, $action );
        $this->execMaj( 'ROLE', 'STRUCTURE_ID', $ids, $action );
        return $this;
    }

    /**
     * Import d'un, plusieurs ou tous les personnels
     *
     * @param string|array|null $sourceCode  Identifiant source du personnel
     * @param string            $action      Action
     * @retun self
     */
    public function personnel( $sourceCode=null, $action=self::A_ALL )
    {
        $this->execMaj( 'PERSONNEL', 'SOURCE_CODE', $sourceCode, $action );
        return $this;
    }

    /**
     * Import d'un, plusieurs ou tous les établissements
     *
     * @param string|array|null $sourceCode  Identifiant source de l\'établissement
     * @param string            $action      Action
     * @retun self
     */
    public function etablissement( $sourceCode=null, $action=self::A_ALL )
    {
        $this->execMaj( 'ETABLISSEMENT', 'SOURCE_CODE', $sourceCode, $action );
        return $this;
    }

    /**
     * Import d'un, plusieurs ou toutes les sections CNU
     *
     * @param string|array|null $sourceCode  Identifiant source de la section CNU
     * @param string            $action      Action
     * @retun self
     */
    public function sectionCnu( $sourceCode=null, $action=self::A_ALL )
    {
        $this->execMaj( 'SECTION_CNU', 'SOURCE_CODE', $sourceCode, $action );
        return $this;
    }

    /**
     * Import d'un, plusieurs ou tous les corps
     *
     * @param string|array|null $sourceCode  Identifiant source du corps
     * @param string            $action      Action
     * @retun self
     */
    public function corps( $sourceCode=null, $action=self::A_ALL )
    {
        $this->execMaj( 'CORPS', 'SOURCE_CODE', $sourceCode, $action );
        return $this;
    }

    /**
     * Import d'un ou plusieurs ou tous les intervenants
     *
     * @param string|array|null $sourceCode  Identifiant source de l\'intervenant
     * @param string            $action      Action
     * @return self
     */
    public function intervenant( $sourceCode=null, $action=self::A_ALL )
    {
        $this->execMaj( 'INTERVENANT', 'SOURCE_CODE', $sourceCode, $action );
        $this->execMaj( 'INTERVENANT_PERMANENT', 'SOURCE_CODE', $sourceCode, $action );
        $this->execMaj( 'INTERVENANT_EXTERIEUR', 'SOURCE_CODE', $sourceCode, $action );

        $ids = $this->getQueryGenerator()->getIdFromSourceCode( 'INTERVENANT', $sourceCode );
        $this->execMaj( 'ADRESSE_INTERVENANT', 'INTERVENANT_ID', $ids, $action );
        $this->execMaj( 'AFFECTATION_RECHERCHE', 'INTERVENANT_ID', $ids, $action );
        return $this;
    }

    /**
     * Import d'un, plusieurs ou toutes les périodes d'enseignement
     *
     * @param string|array|null $sourceCode  Identifiant source
     * @param string            $action      Action
     * @retun self
     */
    public function periodeEnseignement( $sourceCode=null, $action=self::A_ALL )
    {
        $this->execMaj( 'PERIODE_ENSEIGNEMENT', 'SOURCE_CODE', $sourceCode, $action );
        return $this;
    }

    /**
     * Import d'un, plusieurs ou tous groupes de type de formation
     *
     * @param string|array|null $sourceCode  Identifiant source
     * @param string            $action      Action
     * @retun self
     */
    public function groupeTypeFormation( $sourceCode=null, $action=self::A_ALL )
    {
        $this->execMaj( 'GROUPE_TYPE_FORMATION', 'SOURCE_CODE', $sourceCode, $action );
        return $this;
    }

    /**
     * Import d'un, plusieurs ou tous les types de formation
     *
     * @param string|array|null $sourceCode  Identifiant source
     * @param string            $action      Action
     * @retun self
     */
    public function typeFormation( $sourceCode=null, $action=self::A_ALL )
    {
        $this->execMaj( 'TYPE_FORMATION', 'SOURCE_CODE', $sourceCode, $action );
        return $this;
    }

    /**
     * Import d'un, plusieurs ou toutes les étapes
     *
     * @param string|array|null $sourceCode  Identifiant source
     * @param string            $action      Action
     * @retun self
     */
    public function etape( $sourceCode=null, $action=self::A_ALL )
    {
        $this->execMaj( 'ETAPE', 'SOURCE_CODE', $sourceCode, $action );
        return $this;
    }

    /**
     * Construit et exécute la reqûete d\'interrogation des vues différentielles
     *
     * @param string            $tableName   Nom de la table
     * @param string            $name        Nom du champ à tester
     * @param string|array|null $value       Valeur de test du champ
     * @param string            $action      Action
     * @retun self
     */
    protected function execMaj( $tableName, $name, $value=null, $action=self::A_ALL )
    {
        $query = new Query($tableName);
        if (null !== $value) $query->addColValue($name, $value);
        switch( $action ){
            case 'insert':
                $query->setAction (array(Query::ACTION_INSERT,Query::ACTION_UNDELETE));
            break;
            case 'update':
                $query->setAction (array(Query::ACTION_UPDATE,Query::ACTION_DELETE));
            break;
        }
        $this->getQueryGenerator()->execMaj($query);
        return $this;
    }

}