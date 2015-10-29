<?php

namespace Import\Processus;

use Import\Entity\Differentiel\Query;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Personnel;
use Application\Entity\Db\Etablissement;
use Application\Entity\Db\SectionCnu;
use Application\Entity\Db\Corps;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\GroupeTypeFormation;
use Application\Entity\Db\TypeFormation;
use Application\Entity\Db\Etape;
use Zend\ServiceManager\ServiceLocatorAwareInterface;


/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Import implements ServiceLocatorAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait,
        \Application\Service\Traits\ContextAwareTrait
    ;

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
        return $this->getServiceLocator()->get('importServiceQueryGenerator');
    }

    /**
     * Retourne le service différentiel
     *
     * @return \Import\Service\Differentiel
     */
    protected function getDifferentiel()
    {
        return $this->getServiceLocator()->get('importServiceDifferentiel');
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

        $id = $this->getQueryGenerator()->getIdFromSourceCode( 'STRUCTURE', $sourceCode );
        if (! empty($id)){
            $this->execMaj( 'ADRESSE_STRUCTURE', 'STRUCTURE_ID', $id, $action );
            $this->execMaj( 'ROLE', 'STRUCTURE_ID', $id, $action );
        }
        return $this;
    }

    /**
     * Retourne les lignes de différentiel correspondantes à la structure
     *
     * @param Structure $structure
     * @return Ligne[]|array()
     */
    public function structureGetDifferentiel( Structure $structure )
    {
        $differentiel = $this->getDifferentiel();

        $q1 = new Query('STRUCTURE');
        $q1->setSourceCode($structure->getSourceCode());

        $q2 = new Query('ADRESSE_STRUCTURE');
        $q2->addColValue( 'STRUCTURE_ID', $structure->getId() );

        $q3 = new Query('ROLE');
        $q3->addColValue( 'STRUCTURE_ID', $structure->getId() );

        $diff = array_merge(
            $differentiel->make($q1)->fetchAll(),
            $differentiel->make($q2)->fetchAll(),
            $differentiel->make($q3)->fetchAll()
        );

        return $diff;
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
     * Retourne les lignes de différentiel correspondantes au personnel
     *
     * @param Personnel $personnel
     * @return Ligne[]|array()
     */
    public function personnelGetDifferentiel( Personnel $personnel )
    {
        $q = new Query('personnel');
        $q->setSourceCode($personnel->getSourceCode());
        $diff = $this->getDifferentiel()->make($q)->fetchAll();

        return $diff;
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
     * Retourne les lignes de différentiel correspondantes à l'établissement
     *
     * @param Etablissement $etablissement
     * @return Ligne[]|array()
     */
    public function etablissementGetDifferentiel( Etablissement $etablissement )
    {
        $q = new Query('etablissement');
        $q->setSourceCode($etablissement->getSourceCode());
        $diff = $this->getDifferentiel()->make($q)->fetchAll();

        return $diff;
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
     * Retourne les lignes de différentiel correspondantes à la section CNU
     *
     * @param SectionCnu $sectionCnu
     * @return Ligne[]|array()
     */
    public function sectionCnuGetDifferentiel( SectionCnu $sectionCnu )
    {
        $q = new Query('sectionCnu');
        $q->setSourceCode($sectionCnu->getSourceCode());
        $diff = $this->getDifferentiel()->make($q)->fetchAll();

        return $diff;
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
     * Retourne les lignes de différentiel correspondantes au corps
     *
     * @param Corps $corps
     * @return Ligne[]|array()
     */
    public function corpsGetDifferentiel( Corps $corps )
    {
        $q = new Query('corps');
        $q->setSourceCode($corps->getSourceCode());
        $diff = $this->getDifferentiel()->make($q)->fetchAll();

        return $diff;
    }

    /**
     * Import d'un ou plusieurs ou tous les intervenants
     *
     * @param string|array|null $sourceCode  Identifiant source de l\'intervenant
     * @param string            $action      Action
     * @return self
     */
    public function intervenant( $sourceCode=null, $action=null )
    {
        $this->execMaj( 'INTERVENANT', 'SOURCE_CODE', $sourceCode, $action ?: self::A_INSERT );
        $id = $this->getQueryGenerator()->getIdFromSourceCode( 'INTERVENANT', $sourceCode, $this->getServiceContext()->getAnnee()->getId() );
        if (! empty($id)){
            $this->execMaj( 'ADRESSE_INTERVENANT', 'INTERVENANT_ID', $id, $action ?: self::A_ALL );
            $this->execMaj( 'AFFECTATION_RECHERCHE', 'INTERVENANT_ID', $id, $action ?: self::A_ALL );
        }
        return $this;
    }

    /**
     * Retourne les lignes de différentiel correspondantes à l'intervenant
     *
     * @param Intervenant $intervenant
     * @return Ligne[]|array()
     */
    public function intervenantGetDifferentiel( Intervenant $intervenant )
    {
        $differentiel = $this->getDifferentiel();

        $q1 = new Query('INTERVENANT');
        $q1->setSourceCode($intervenant->getSourceCode());

        $q2 = new Query('ADRESSE_INTERVENANT');
        $q2->addColValue('INTERVENANT_ID', $intervenant->getId() );

        $q3 = new Query('AFFECTATION_RECHERCHE');
        $q3->addColValue('INTERVENANT_ID', $intervenant->getId() );

        $diff = array_merge(
            $differentiel->make($q1)->fetchAll(),
            $differentiel->make($q2)->fetchAll(),
            $differentiel->make($q3)->fetchAll()
        );

        return $diff;
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
     * Retourne les lignes de différentiel correspondantes aux groupes de types de formation
     *
     * @param GroupeTypeFormation $groupeTypeFormation
     * @return Ligne[]|array()
     */
    public function groupeTypeFormationGetDifferentiel( GroupeTypeFormation $groupeTypeFormation )
    {
        $q = new Query('groupeTypeFormation');
        $q->setSourceCode($groupeTypeFormation->getSourceCode());
        $diff = $this->getDifferentiel()->make($q)->fetchAll();

        return $diff;
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
     * Retourne les lignes de différentiel correspondantes aux types de formation
     *
     * @param TypeFormation $typeFormation
     * @return Ligne[]|array()
     */
    public function typeFormationGetDifferentiel( TypeFormation $typeFormation )
    {
        $q = new Query('typeFormation');
        $q->setSourceCode($typeFormation->getSourceCode());
        $diff = $this->getDifferentiel()->make($q)->fetchAll();

        return $diff;
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
     * Retourne les lignes de différentiel correspondantes aux étapes
     *
     * @param Etape $etape
     * @return Ligne[]|array()
     */
    public function etapeGetDifferentiel( Etape $etape )
    {
        $q = new Query('etape');
        $q->setSourceCode($etape->getSourceCode());
        $diff = $this->getDifferentiel()->make($q)->fetchAll();

        return $diff;
    }

    /**
     * Construit et exécute la reqûete d'interrogation des vues différentielles
     *
     * @param string            $tableName   Nom de la table
     * @param string            $name        Nom du champ à tester
     * @param string|null       $value       Valeur de test du champ
     * @param string            $action      Action
     * @retun self
     */
    protected function execMaj( $tableName, $name, $value=null, $action=self::A_ALL )
    {
        if ('SOURCE_CODE' == $name && $value !== null){
            $value = (string)$value;
        }
        $query = new Query($tableName);
        if (null !== $value) $query->addColValue($name, $value);
        switch( $action ){
            case 'insert':
                $query->setAction ([Query::ACTION_INSERT,Query::ACTION_UNDELETE]);
            break;
            case 'update':
                $query->setAction ([Query::ACTION_UPDATE,Query::ACTION_DELETE]);
            break;
        }
        $this->getQueryGenerator()->execMaj($query);
        return $this;
    }

}