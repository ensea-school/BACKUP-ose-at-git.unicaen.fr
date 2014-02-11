<?php

namespace Import\Model\Connecteur;

use Import\Model\Entity\Entity;
use Import\Model\Entity\Intervenant\Intervenant as IntervenantEntity;
use Import\Model\Entity\Intervenant\Adresse as IntervenantAdresseEntity;
use Import\Model\Entity\Structure\Structure as StructureEntity;
use DateTime;
use Import\Model\Exception;
use Import\Model\Hydrator\Oracle as OracleHydrator;

/**
 * Connecteur Harpège
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Harpege extends Connecteur {



    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'Harpège';
    }

    /**
     * Recherche d'un intervenant à partir des données source
     *
     * @param string @criterion Critère de recherche
     * @return array
     */
    public function searchIntervenant( $criterion )
    {

        $params = array(
            'criterionId' => (integer)$criterion,
            'limit'       => 100
        );

        $criterion = explode( ' ', $criterion );

        $sqlCrit = '';
        for( $i=0; $i<count($criterion); $i++ ){
            if ('' != $sqlCrit) $sqlCrit .= ' AND ';
            $sqlCrit .= "(lower(CONVERT(nom_usuel,'US7ASCII')) LIKE LOWER(CONVERT(:criterionStr$i,'US7ASCII'))"
                     ." OR lower(CONVERT(nom_patronymique,'US7ASCII')) LIKE LOWER(CONVERT(:criterionStr$i,'US7ASCII'))"
                     ." OR lower(CONVERT(prenom,'US7ASCII')) LIKE LOWER(CONVERT(:criterionStr$i,'US7ASCII')))";
            $params["criterionStr$i"] = '%'.$criterion[$i].'%';
        }

        $sql = <<<EOS
        SELECT DISTINCT
            source_code,
            civilite_id,
            UPPER(nom_usuel) nom_usuel,
            prenom,
            date_naissance
        FROM
            V_HARP_INTERVENANT
        WHERE
            (source_code = :criterionId OR ($sqlCrit))
            AND rownum <= :limit
        ORDER BY
            nom_usuel, prenom
EOS;

        $stmt = $this->query($sql, $params );

        $result = array();
        while( $r = $stmt->fetch() ){
            $dateNaissance = new DateTime( $r['DATE_NAISSANCE'] );

            $result[$r['SOURCE_CODE']] = array(
               'id'    => $r['SOURCE_CODE'],
               'label' => $r['NOM_USUEL'].' '.$r['PRENOM'],
               'extra' => '(n° '.$r['SOURCE_CODE'].', né'.('M.' == $r['CIVILITE_ID'] ? '' : 'e').' le '.$dateNaissance->format('d/m/Y').')',
            );
        }
        return $result;
    }

    /**
     *
     * @param string $id Identifiant de l'intervenant
     * @return IntervenantEntity
     */
    public function getIntervenant( $id )
    {
        $sql = "SELECT * FROM V_HARP_INTERVENANT WHERE source_code = :id";
        $stmt = $this->query( $sql, array(
            'id' => (integer)$id
        ) );
        if (($result = $stmt->fetch())){
            return $this->hydrateEntity($result, new IntervenantEntity() );
        }else{
            throw new Exception('Intervenant non trouvé');
        }
    }

    /**
     * Retourne la liste des adresses d'un intervenant
     *
     * @param string $id Identifiant de l'intervenant
     * @return IntervenantAdresseEntity[]
     */
    public function getIntervenantAdresses( $id )
    {
        $sql = "SELECT * FROM V_HARP_ADRESSE_INTERVENANT WHERE z_intervenant_id = :id";
        $stmt = $this->query( $sql, array(
            'id' => (integer)$id
        ) );
        $result = array();
        while($r = $stmt->fetch()){
            $result[] = $this->hydrateEntity($result, new IntervenantAdresseEntity() );
        }
        return $result;
    }

    /**
     * Retourne la liste des identifiants source des structures
     *
     * @return string[]
     * @throws Exception
     */
    public function getStructureList()
    {
        $sql = "SELECT SOURCE_CODE FROM V_HARP_STRUCTURE";
        $stmt = $this->query( $sql );
        $result = array();
        while($r = $stmt->fetch()){
            $result[] = $r['SOURCE_CODE'];
        }
        return $result;
    }

    /**
     * Retourne une structure à partir de son identifiant
     *
     * @param string $id Identifiant de la structure
     * @return StructureEntity
     * @throws Exception
     */
    public function getStructure( $id )
    {
        $sql = "SELECT * FROM V_HARP_STRUCTURE WHERE SOURCE_CODE = :id";
        $stmt = $this->query( $sql, array(
            'id' => (string)$id
        ) );
        if (($result = $stmt->fetch())){
            return $this->hydrateEntity($result, new StructureEntity() );
        }else{
            throw new Exception('Structure non trouvée');
        }
    }

    /**
     * Effectue une requête SQL
     *
     * @param type $sql Code SQL
     * @param array $params Tableau de paramètres à transmettre à la requête
     * @return \Doctrine\DBAL\Driver\Statement
     */
    protected function query( $sql, array $params=array() )
    {
        $em = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');/* @var $em \Doctrine\ORM\EntityManager */
        $stmt = $em->getConnection()->prepare($sql);
        foreach( $params as $name => $value ){
            $stmt->bindValue($name, $value);
        }
        $stmt->setFetchMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $stmt->execute();
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
                    throw new Exception('Impossible d\'importer la . Il  dans OSE');
                }
            }
        }

        $hydrator = new OracleHydrator();
        $hydrator->makeStrategies($entity);
        $hydrator->hydrate($data, $entity);
        return $entity;
    }


}