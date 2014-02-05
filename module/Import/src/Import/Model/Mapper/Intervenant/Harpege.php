<?php

namespace Import\Model\Mapper\Intervenant;

use DateTime;

class Harpege extends Intervenant {

    /**
     * recherche un ensemble d'enseignants
     *
     * @param string $term
     * @return array[]
     */
    public function search( $term, $limit=100 )
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getServiceManager()->get('doctrine.entitymanager.orm_snort');

        $sql = <<<EOS
        SELECT
            personnel_id,
            civilite_id,
            nom_usuel,
            prenom,
            date_naissance
        FROM
            V_HARP_INTERVENANT
        WHERE
            (personnel_id = :termId
            OR lower(nom_usuel) like :termStr
            OR lower(nom_patronymique) like :termStr)
            AND rownum <= :limit
        ORDER BY
            nom_usuel, prenom
EOS;

        /* @var $stmt \Doctrine\DBAL\Driver\Statement */
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue( 'termId', (integer)$term );
        $stmt->bindValue( 'termStr', '%'.strtolower($term).'%' );
        $stmt->bindValue( 'limit', $limit );
        $stmt->execute();

        $result = array();
        while( $r = $stmt->fetch(\Doctrine\ORM\Query::HYDRATE_ARRAY)){
            $dateNaissance = new DateTime( $r['DATE_NAISSANCE'] );

            $result[] = array(
               'id'    => $r['PERSONNEL_ID'],
               'label' => $r['PRENOM'].' '.$r['NOM_USUEL'],
               'extra' => '(n° '.$r['PERSONNEL_ID'].', né'.('M.' == $r['CIVILITE_ID'] ? '' : 'e').' le '.$dateNaissance->format('d/m/Y').')',
            );
        }
        return $result;
    }

}