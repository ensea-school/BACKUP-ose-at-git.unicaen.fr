<?php

namespace Import\Model\Connecteur;

use Import\Model\Entity\Intervenant\Intervenant as IntervenantEntity;
use Import\Model\Entity\Intervenant\Adresse as IntervenantAdresseEntity;
use DateTime;
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
            $sqlCrit .= "(lower(nom_usuel) LIKE :criterionStr$i"
                     ." OR lower(nom_patronymique) LIKE :criterionStr$i"
                     ." OR lower(prenom) LIKE :criterionStr$i)";
            $params["criterionStr$i"] = '%'.strtolower($criterion[$i]).'%';
        }

        $sql = <<<EOS
        SELECT DISTINCT
            source_id,
            civilite_id,
            UPPER(nom_usuel) nom_usuel,
            prenom,
            date_naissance
        FROM
            V_HARP_INTERVENANT
        WHERE
            (source_id = :criterionId OR ($sqlCrit))
            AND rownum <= :limit
        ORDER BY
            nom_usuel, prenom
EOS;

        $stmt = $this->query($sql, $params );

        $result = array();
        while( $r = $stmt->fetch() ){
            $dateNaissance = new DateTime( $r['DATE_NAISSANCE'] );

            $result[$r['SOURCE_ID']] = array(
               'id'    => $r['SOURCE_ID'],
               'label' => $r['NOM_USUEL'].' '.$r['PRENOM'],
               'extra' => '(n° '.$r['SOURCE_ID'].', né'.('M.' == $r['CIVILITE_ID'] ? '' : 'e').' le '.$dateNaissance->format('d/m/Y').')',
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
        $sql = "SELECT * FROM V_HARP_INTERVENANT WHERE source_id = :id";
        $stmt = $this->query( $sql, array(
            'id' => (integer)$id
        ) );
        if (($result = $stmt->fetch())){
            $entity = new IntervenantEntity();
            $hydrator = new OracleHydrator();
            $hydrator->makeStrategies($entity);
            $hydrator->hydrate($result, $entity);
            return $entity;
        }
        return null;
    }

    /**
     * Retourne la liste des adresses d'un intervenant
     *
     * @param string $id Identifiant de l'intervenant
     * @return \Import\Model\Entity\Intervenant\Adresse[]
     */
    public function getIntervenantAdresses( $id )
    {
        $sql = "SELECT * FROM V_HARP_ADRESSE_INTERVENANT WHERE c_intervenant_id = :id";
        $stmt = $this->query( $sql, array(
            'id' => (integer)$id
        ) );
        $result = array();
        $hydrator = new OracleHydrator();
        $hydrator->makeStrategies(new IntervenantAdresseEntity());
        while($r = $stmt->fetch()){
            $entity = new IntervenantAdresseEntity();
            $hydrator->hydrate($r, $entity);
            $result[] = $entity;
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
    protected function query( $sql, array $params )
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

}