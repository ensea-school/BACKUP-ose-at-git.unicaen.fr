<?php

namespace Import\Model\Processus;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Import\Model\Entity\Entity as SourceEntity;
use Import\Model\Exception\AllreadyExists as AllreadyExistsException;
use Doctrine\ORM\EntityManager;
use Application\Entity\Db\Historique;
use stdClass;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Import implements ServiceManagerAwareInterface
{
    /**
     * schéma de BDD
     *
     * @var array
     */
    protected static $schema = array();

    /**
     *
     * @var \Application\Entity\Db\Utilisateur 
     */
    protected $user;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;



    /**
     * Import d'une structure
     *
     * @param string $id                Identifiant source de la structure
     * @param boolean $force            Force la mise à jour en cas d'import précédent
     * @return self
     */
    public function structure( $id, $force=false )
    {
        $entityNS = '\\Application\\Entity\\Db\\';
        $source = $this->getServiceManager()->get('importServiceStructure')->get( $id );
        $dest = $this->createAndHydrate( $entityNS.'Structure', $source, $force, array(
            'type'          => $entityNS.'TypeStructure',
            'source'        => $entityNS.'Source',
            'parente'       => $entityNS.'Structure',
            //'etablissement' => $entityNS.'Etablissement',
        ) );

        /** @todo Remplacer l'établissement par le bon paramètre ! ! ! */
        $dest->setEtablissement( $this->getEntityManager()->find($entityNS.'Etablissement', 275) );
        $this->save($dest, true);
        return $this;
    }

    /**
     * Import d'un établissement
     *
     * @param string|array $id          Identifiant source de l'établissement
     * @param boolean $force            Force la mise à jour en cas d'import précédent
     * @retun self
     */
    public function etablissement( $id, $force=false )
    {
        if (! is_array($id)) $id = array($id);

        $entityNS = '\\Application\\Entity\\Db\\';
        $sources = $this->getServiceManager()->get('importServiceStructure')->getEtablissement( $id );
        foreach( $sources as $source ){
//            $this->insert('etablissement', $source);
            $dest = $this->createAndHydrate( $entityNS.'Etablissement', $source, $force, array(
                'source'        => $entityNS.'Source',
            ) );
            $this->save($dest, false);
        };
        $this->getEntityManager()->flush();
        return $this;
    }

    /**
     * Import d'un intervenant
     *
     * @param string $id        Identifiant source de l'intervenant
     * @param boolean $force    Force la mise à jour en cas d'import précédent
     * @return self
     */
    public function intervenant( $id, $force=false )
    {
        $entityNS = '\\Application\\Entity\\Db\\';
        $source = $this->getServiceManager()->get('importServiceIntervenant')->get( $id );  /* @var $source \Import\Model\Entity\Intervenant\Intervenant */

        switch( $source->getTypeId() ){
        case 1: $className = $entityNS.'IntervenantPermanent';
            $sourceP = $this->getServiceManager()->get('importServiceIntervenant')->getPermanent( $id );
            $dest = $this->createAndHydrate($className, $sourceP, $force, array(
                'corps'             => $entityNS.'Corps',
                'sectionCnu'        => $entityNS.'SectionCnu',
            ) );
        break;
        case 2: $className = $entityNS.'IntervenantExterieur';
            $sourceE = $this->getServiceManager()->get('importServiceIntervenant')->getExterieur( $id );
            $dest = $this->createAndHydrate($className, $sourceE, $force, array(
                'typeIntervenantExterieur'  => $entityNS.'TypeIntervenantExterieur',
                'situationFamiliale'        =>  $entityNS.'SituationFamiliale',
                'regimeSecu'                =>  $entityNS.'RegimeSecu',
                'typePoste'                 =>  $entityNS.'TypePoste',
            ) );
        break;
        }

        $dest = $this->createAndHydrate( $dest, $source, $force, array(
            'civilite'        => $entityNS.'Civilite',
            'source'          => $entityNS.'Source',
          // 'type'            => $entityNS.'TypeIntervenant',
            //'etablissement' => $entityNS.'Etablissement',
        ) );

        $this->save($dest, true);

        /* Adresses de l'intervenant */
        $sources = $this->getServiceManager()->get('importServiceIntervenant')->getAdresses( $id );
        foreach( $sources as $index => $s ){
            $d = $this->createAndHydrate( $entityNS.'AdresseIntervenant', $s, $force, array(
                'source'          => $entityNS.'Source',
            ) );
            $d->setIntervenant( $dest );
            $this->save($d, true);
        }
        return $this;
    }

    /**
     * @param string|stdClass $entity   Nom de la classe à créer ou classe à créer
     * @return stdClass
     */
    protected function createAndHydrate( $entity, SourceEntity $source, $force = false, array $relations=array() )
    {
        /* Initialisation */
        if (is_string($entity)){
            $exists = $this->getEntityManager()->getRepository($entity)->findBy(array(
                'sourceCode' => $source->getSourceCode()
            ));
            if ($exists && ! $force){
                throw new AllreadyExistsException('L\'entité de type '.$entity.' existe déjà');
            }

            /* Initialisation */
            if ($exists){
                $dest = $exists[0];
            }else{
                $dest = new $entity;
            }
        }else{
            $dest = $entity;
        }

        /* Remplissage */
        $hydrator = new ClassMethods();
        $data = $hydrator->extract($source);
        $hydrator->hydrate($data, $dest);

        /*Création des relations */
        foreach( $relations as $fieldName => $relationClass ){
            $this->addRelation( $source, $dest, $fieldName, $relationClass );
        }

        /* Import des historiques */
        if (null !== $source->getHistoDebut() || null !== $source->getHistoFin()){
            $historique = $dest->getHistorique();
            if (null === $historique){
                $historique = new Historique();
                $historique->setDebut ($source->getHistoDebut());
                $historique->setFin ($source->getHistoFin());
                $dest->setHistorique( $historique );
                $this->getEntityManager()->persist($historique);
            }
        }
        return $dest;
    }

    /**
     * Ajoute une relation entre deux tables
     * 
     * @param SourceEntity $source  Objet source
     * @param mixed $dest           Objet destination
     * @param string $fieldName     Champ contenant l'entité étrangère (transmis en camelCase)
     * @param string $entityClass   Nom de la classe d'entité étrangère
     * @return self
     */
    protected function addRelation( SourceEntity $source, $dest, $fieldName, $entityClass )
    {
        $id = call_user_func( array($source,'get'.ucfirst($fieldName).'Id') );
        if (null !== $id){
            $object = $this->getEntityManager()->find($entityClass, $id);
            call_user_func( array($dest,'set'.ucfirst($fieldName)), $object );
        }
        return $this;
    }

    /**
     * Sauvegarde d'une entité
     * 
     * @param mixed $entity
     * @param boolean $flush
     * @return self
     */
    public function save( $entity, $flush=false )
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($entity);
        if ($flush) $entityManager->flush();
        return $this;
    }

    /**
     * Retourne le gestionnaire d'entités Doctrine
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
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

    /**
     * Insère un nouvel enregistrement dans une table à partir d'une entité
     *
     * @param string $tableName
     * @param SourceEntity $entity
     */
    public function insert($tableName, SourceEntity $entity)
    {
        $schema = $this->getSchema($tableName);
        $hydrator = new ClassMethods();
        $data = $hydrator->extract($entity);
        $tblData = array();
        foreach( $schema as $col => $cp ){
            if (isset($data[$col])){
                $tblData[$col] = $data[$col];
            }
        }

        $sequenceGenerator = new \Doctrine\ORM\Id\SequenceGenerator('ETABLISSEMENT_ID_SEQ', 1);
        $newId = $sequenceGenerator->generate($this->getEntityManager(), new \stdClass);


        $tblData['id'] = $newId;
        $historique = new Historique();
        $historique->setDebut (new \DateTime);
        $historique->setModification(new \DateTime);
        $historique->setCreateur($this->getUser());
        $historique->setModificateur($this->getUser());
        $this->getEntityManager()->persist($historique);
        $this->getEntityManager()->flush();
        $tblData['historique_id'] = $historique->getId();
        $this->getEntityManager()->getConnection()->insert($tableName, $tblData);
    }

    protected function getUser(){
        if (null === $this->user){
            if (($identity = $this->getServiceManager()->get('Zend\Authentication\AuthenticationService')->getIdentity())) {
                if (isset($identity['db']) && $identity['db'] instanceof \Application\Entity\Db\Utilisateur) {
                    $this->user = $identity['db'];
                }
            }
        }
        return $this->user;
    }

    /**
     * Retourne le schéma de la table demandée
     * 
     * @param string $tableName
     * @return array
     */
    protected function getSchema( $tableName )
    {
        if (! isset(self::$schema[$tableName])){
            $sql = <<<EOS
                SELECT
                    lower(column_name) as column_name,
                    data_type,
                    data_length,
                    nullable,
                    data_default
                FROM
                    SYS.USER_TAB_COLS
                WHERE
                    table_name = upper(:table_name)
                ORDER BY
                    column_id
EOS;
            $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, array('table_name' => $tableName) );
            $schema = array();
            while( $c = $stmt->fetch() ){
                $schema[$c['COLUMN_NAME']] = array(
                    'data_type'    => $c['DATA_TYPE'],
                    'data_length'  => $c['DATA_LENGTH'],
                    'nullable'     => $c['NULLABLE'],
                    'data_default' => $c['DATA_DEFAULT'],
                );
            }
            if (empty($schema)){
                throw new \Import\Model\Exception\Exception('La table transmise ('.$tableName.') n\'existe pas.');
            }
            self::$schema[$tableName] = $schema;
        }
        return self::$schema[$tableName];
    }
}