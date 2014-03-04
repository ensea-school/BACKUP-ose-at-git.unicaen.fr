<?php

namespace Import\Processus;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Doctrine\ORM\EntityManager;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Import implements ServiceManagerAwareInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * ID l'utilisateur courant
     *
     * @var integer
     */
    protected $currentUser;





    /**
     * Retourne l'ID de l'utilisateur courant
     *
     * @return integer
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }



    /**
     * Affecte un ID d'utilisateur courant pour les modifications
     *
     * @param integer $currentUser
     * @return self
     */
    public function setCurrentUser($currentUser)
    {
        $this->currentUser = (integer)$currentUser;
        return $this;
    }



    public function updateViewsAndPackages()
    {
        $this->getServiceManager()->get('importServiceQueryGenerator')->updateAll();
    }



    /**
     * Import d'une ou plusieurs structures
     *
     * @param string|array|null  $sourceCode  Identifiant source de la structure
     * @param boolean            $force       Force la mise à jour en cas d'import précédent
     * @return self
     */
    public function structure( $sourceCode=null, $force=true )
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = $this->makeMajQuery( 'STRUCTURE', 'SOURCE_CODE', $sourceCode, $force );
        $connection->exec($sql);

        $ids = $this->getIdFromSourceCode( 'STRUCTURE', $sourceCode );

        $sql = $this->makeMajQuery( 'ADRESSE_STRUCTURE', 'STRUCTURE_ID', $ids, $force );
        $connection->exec($sql);

        $sql = $this->makeMajQuery( 'ROLE', 'STRUCTURE_ID', $ids, $force );
        $connection->exec($sql);

        return $this;
    }

    /**
     * Import d'un, plusieurs ou tous les personnels
     *
     * @param string|array|null  $sourceCode  Identifiant source du personnel
     * @param boolean            $force       Force la mise à jour en cas d'import précédent
     * @retun self
     */
    public function personnel( $sourceCode=null, $force=true )
    {
        $sql = $this->makeMajQuery( 'PERSONNEL', 'SOURCE_CODE', $sourceCode, $force );
        $this->getEntityManager()->getConnection()->exec($sql);
        return $this;
    }

    /**
     * Import d'un, plusieurs ou tous les établissements
     *
     * @param string|array|null  $sourceCode  Identifiant source de l\'établissement
     * @param boolean            $force       Force la mise à jour en cas d'import précédent
     * @retun self
     */
    public function etablissement( $sourceCode=null, $force=true )
    {
        $sql = $this->makeMajQuery( 'ETABLISSEMENT', 'SOURCE_CODE', $sourceCode, $force );
        $this->getEntityManager()->getConnection()->exec($sql);
        return $this;
    }

    /**
     * Import d'un, plusieurs ou toutes les sections CNU
     *
     * @param string|array|null  $sourceCode  Identifiant source de la section CNU
     * @param boolean            $force       Force la mise à jour en cas d'import précédent
     * @retun self
     */
    public function sectionCnu( $sourceCode=null, $force=true )
    {
        $sql = $this->makeMajQuery( 'SECTION_CNU', 'SOURCE_CODE', $sourceCode, $force );
        $this->getEntityManager()->getConnection()->exec($sql);
        return $this;
    }

    /**
     * Import d'un, plusieurs ou tous les corps
     *
     * @param string|array|null  $sourceCode  Identifiant source du corps
     * @param boolean            $force       Force la mise à jour en cas d'import précédent
     * @retun self
     */
    public function corps( $sourceCode=null, $force=true )
    {
        $sql = $this->makeMajQuery( 'CORPS', 'SOURCE_CODE', $sourceCode, $force );
        $this->getEntityManager()->getConnection()->exec($sql);
        return $this;
    }

    /**
     * Import d'un ou plusieurs ou tous les intervenants
     *
     * @param string|array|null  $sourceCode  Identifiant source de l\'intervenant
     * @param boolean            $force       Force la mise à jour en cas d'import précédent
     * @return self
     */
    public function intervenant( $sourceCode=null, $force=true )
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = $this->makeMajQuery( 'INTERVENANT', 'SOURCE_CODE', $sourceCode, $force );
        $connection->exec($sql);

        $sql = $this->makeMajQuery( 'INTERVENANT_PERMANENT', 'SOURCE_CODE', $sourceCode, $force );
        $connection->exec($sql);

        $sql = $this->makeMajQuery( 'INTERVENANT_EXTERIEUR', 'SOURCE_CODE', $sourceCode, $force );
        $connection->exec($sql);

        $ids = $this->getIdFromSourceCode( 'INTERVENANT', $sourceCode );

        $sql = $this->makeMajQuery( 'ADRESSE_INTERVENANT', 'INTERVENANT_ID', $ids, $force );
        $connection->exec($sql);

        $sql = $this->makeMajQuery( 'AFFECTATION_RECHERCHE', 'INTERVENANT_ID', $ids, $force );
        $connection->exec($sql);

        return $this;
    }

    /**
     * Retourne les identifiants des données concernés
     *
     * @param string|string[]|null  $sourceCode
     * @return integer[]|null
     */
    protected function getIdFromSourceCode( $tableName, $sourceCode )
    {
        $ids = null;
        if (! empty($sourceCode)){
            if (! is_array($sourceCode)){
                $sc = array($sourceCode);
            }else{
                $sc = $sourceCode;
            }
            $sql = 'SELECT id FROM '.$tableName.' WHERE SOURCE_CODE IN (:sourceCode)';
            $stmt = $this->getEntityManager()->getConnection()->executeQuery(
                                                                    $sql,
                                                                    array('sourceCode' => $sc),
                                                                    array('sourceCode' => \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
                                                                );
            $ids = array();
            while($r = $stmt->fetch()){
                $ids[] = $r['ID'];
            }
        }
        return $ids;
    }

    /**
     * Construit la reqûete d\'interrogation des vues différentielles
     *
     * @param string             $tableName   Nom de la table
     * @param string             $name        Nom du champ à tester
     * @param string|array|null  $value       Valeur de test du champ
     * @param boolean            $force       Force la mise à jour en cas d'import précédent
     * @retun string|null
     */
    protected function makeMajQuery( $tableName, $name, $value=null, $force=true )
    {
        $conditions = array();
        if (is_string($value)){
            $conditions[] = $name.' = '.$this->escape( $value);
        }elseif(is_array($value)){
            $csc = array();
            foreach( $value as $sc ){
                $csc[] = $this->escape( $sc );
            }
            $conditions[] = $name.' IN ('.implode( ',', $csc ).')';
        }
        if (false == $force){
            $conditions[] = 'IMPORT_ACTION IN (\'insert\',\'undelete\')';
        }
        if (! empty($conditions)){
            $conditions = $this->escape('WHERE '.implode( ' AND ', $conditions ));
        }else{
            $conditions = 'NULL';
        }

        $sql = "BEGIN OSE_IMPORT.SET_CURRENT_USER($this->currentUser);OSE_IMPORT.MAJ_$tableName($conditions); END;";
        return $sql;
    }

    /**
     * Retourne le gestionnaire d'entités Doctrine
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if (empty($this->entityManager))
            $this->entityManager = $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
        return $this->entityManager;
    }

    /**
     * Get service manager
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     * @return self
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

}