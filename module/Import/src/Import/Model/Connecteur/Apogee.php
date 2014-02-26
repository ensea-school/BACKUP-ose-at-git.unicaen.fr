<?php

namespace Import\Model\Connecteur;

use Import\Model\Entity\Entity;
use Import\Model\Entity\Structure\Etablissement     as EtablissementEntity;
use Import\Model\Exception\MissingDependency        as MissingDependencyException;
use Import\Model\Hydrator\Oracle                    as OracleHydrator;
use Import\Model\Exception\NotFound                 as NotFoundException;

/**
 * Connecteur Apogée
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Apogee extends Connecteur {



    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Apogée';
    }

    /**
     * {@inheritDoc}
     */
    public function getEtablissement($id)
    {
        $once = ! is_array($id);
        if ($once){
            $id = array($id);
        }
        $sql = "SELECT * FROM V_APO_ETABLISSEMENT";
        $stmt = $this->query( $sql );
        //$sql = "SELECT * FROM V_APO_ETABLISSEMENT WHERE SOURCE_CODE IN (:id)";
        /*$stmt = $this->query( $sql, array(
            'id' => $id
        ) );*/
        $result = array();
        while($r = $stmt->fetch()){
            $result[] = $this->hydrateEntity($r, new EtablissementEntity() );
        }
        if (! isset($result[0])){
            throw new NotFoundException('Etablissement non trouvé');
        }
        if ($once){
            return $result[0];
        }else{
            return $result;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getEtablissementList()
    {
        $sql = "SELECT SOURCE_CODE FROM V_APO_ETABLISSEMENT";
        $stmt = $this->query( $sql );
        $result = array();
        while($r = $stmt->fetch()){
            $result[] = $r['SOURCE_CODE'];
        }
        return $result;
    }

    /**
     * Effectue une requête SQL
     *
     * @param type $sql Code SQL
     * @param array $params Tableau de paramètres à transmettre à la requête
     * @return \Doctrine\DBAL\Driver\Statement
     */
    protected function query( $sql, array $params=null )
    {
        $em = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');/* @var $em \Doctrine\ORM\EntityManager */
        if (null !== $params){
            $types = array();
            foreach( $params as $name => $value ){
                if (is_array($value)) $types[$name] = \Doctrine\DBAL\Connection::PARAM_STR_ARRAY;
            }
            $stmt = $em->getConnection()->executeQuery($sql, $params, $types);
        }else{
            $stmt = $em->getConnection()->executeQuery($sql);
        }
        return $stmt;
    }

    /**
     * Applique les données du tableau à l'entité
     *
     * @param Entity $entity
     * @param array $data
     */
    public function hydrateEntity( array $data, Entity $entity )
    {
        /* Détection des dépendances manquantes */
        foreach( $data as $key => $value ){
            if (0 === strpos($key, 'Z_')){
                $iVal = isset($data[substr($key,2)]) ? $data[substr($key,2)] : null;
                $zVal = $value;
                if (null !== $zVal && null === $iVal){
                    throw new MissingDependencyException('Impossible d\'importer la donnée "'.substr($key,2).'". Elle dépend d\'informations non présentes dans OSE');
                }
            }
        }

        $hydrator = new OracleHydrator();
        $hydrator->makeStrategies($entity);
        $hydrator->hydrate($data, $entity);
        return $entity;
    }
}