<?php

namespace Import\Service;

use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeIntervenant;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Intervenant extends Service {

    /**
     * Recherche un ensemble d'intervenants
     *
     * @param string $criterion
     * @param Structure|null $structure
     * @param TypeIntervenant|null $typeIntervenant
     * @return array[]
     */
    public function searchIntervenant( $criterion, Structure $structure=null, TypeIntervenant $typeIntervenant=null )
    {

        $params = [
            'criterionId' => (integer)$criterion,
            'limit'       => 100
        ];

        $criterion = explode( ' ', $criterion );

        $sqlCrit = '';
        for( $i=0; $i<count($criterion); $i++ ){
            if ('' != $sqlCrit) $sqlCrit .= ' AND ';
            $sqlCrit .= "(lower(CONVERT(i.nom_usuel,'US7ASCII')) LIKE LOWER(CONVERT(:criterionStr$i,'US7ASCII'))"
                     ." OR lower(CONVERT(i.nom_patronymique,'US7ASCII')) LIKE LOWER(CONVERT(:criterionStr$i,'US7ASCII'))"
                     ." OR lower(CONVERT(i.prenom,'US7ASCII')) LIKE LOWER(CONVERT(:criterionStr$i,'US7ASCII')))";
            $params["criterionStr$i"] = '%'.$criterion[$i].'%';
        }

        $where = '';
        if ($structure){
            $where .= 'AND i.structure_id = '.$structure->getId()."\n";
        }
        if ($typeIntervenant){
            $where .= 'AND i.type_id = '.$typeIntervenant->getId()."\n";
        }

        $sql = <<<EOS
        SELECT DISTINCT
            i.source_code,
            c.libelle_court civilite,
            i.nom_usuel,
            i.nom_patronymique,
            decode(c.sexe, 'F', 1, 0) est_une_femme,
            i.prenom,
            i.date_naissance,
            s.libelle_court affectation
        FROM
            SRC_INTERVENANT i
            JOIN CIVILITE c ON (c.ID = i.CIVILITE_ID)
            JOIN STRUCTURE s ON (s.ID = i.STRUCTURE_ID)
        WHERE
            (i.source_code = :criterionId OR ($sqlCrit))
            $where
            AND rownum <= :limit
        ORDER BY
            nom_usuel, prenom
EOS;

        $res = $this->query($sql, $params );

        $result = [];
        $f = new \Application\Filter\IntervenantTrouveFormatter();
        foreach( $res as $r ){
            $result[$r['SOURCE_CODE']] = $f->filter($r);
        }

        return $result;
    }

}