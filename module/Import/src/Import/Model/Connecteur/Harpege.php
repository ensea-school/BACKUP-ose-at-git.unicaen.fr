<?php

namespace Import\Model\Connecteur;

use Import\Model\Entity\Intervenant\Intervenant as IntervenantEntity;
use Import\Model\Entity\Intervenant\Adresse as IntervenantAdresseEntity;
use DateTime;

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
        $sql = <<<EOS
        SELECT DISTINCT
            source_id,
            civilite_id,
            nom_usuel,
            prenom,
            date_naissance
        FROM
            V_HARP_INTERVENANT
        WHERE
            (source_id = :criterionId
            OR lower(nom_usuel) like :criterionStr
            OR lower(nom_patronymique) like :criterionStr)
            AND rownum <= :limit
        ORDER BY
            nom_usuel, prenom
EOS;

        $stmt = $this->query($sql, array(
            'criterionId'    => (integer)$criterion,
            'criterionStr'   => '%'.strtolower($criterion).'%',
            'limit'     => 100
        ) );

        $result = array();
        while( $r = $stmt->fetch() ){
            $dateNaissance = new DateTime( $r['DATE_NAISSANCE'] );

            $result[$r['SOURCE_ID']] = array(
               'id'    => $r['SOURCE_ID'],
               'label' => $r['PRENOM'].' '.$r['NOM_USUEL'],
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
            return new IntervenantEntity( $result );
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
        while($r = $stmt->fetch()){
            $result[] = new IntervenantAdresseEntity( $r );
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
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');

        /* @var $stmt \Doctrine\DBAL\Driver\Statement */
        $stmt = $em->getConnection()->prepare($sql);
        foreach( $params as $name => $value ){
            $stmt->bindValue($name, $value);
        }
        $stmt->setFetchMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $stmt->execute();
        return $stmt;
    }

}