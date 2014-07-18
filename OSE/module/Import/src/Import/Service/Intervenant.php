<?php

namespace Import\Service;

use DateTime;

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
     * @return array[]
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
            $sqlCrit .= "(lower(CONVERT(i.nom_usuel,'US7ASCII')) LIKE LOWER(CONVERT(:criterionStr$i,'US7ASCII'))"
                     ." OR lower(CONVERT(i.nom_patronymique,'US7ASCII')) LIKE LOWER(CONVERT(:criterionStr$i,'US7ASCII'))"
                     ." OR lower(CONVERT(i.prenom,'US7ASCII')) LIKE LOWER(CONVERT(:criterionStr$i,'US7ASCII')))";
            $params["criterionStr$i"] = '%'.$criterion[$i].'%';
        }

        $params['sexeFem'] = \Common\Constants::SEXE_F;

        $sql = <<<EOS
        SELECT DISTINCT
            i.source_code,
            c.libelle_court civilite,
            i.nom_usuel,
            i.nom_patronymique,
            decode(c.sexe, :sexeFem, 1, 0) est_une_femme,
            i.prenom,
            i.date_naissance,
            s.libelle_court affectation
        FROM
            SRC_INTERVENANT i
            JOIN CIVILITE c ON (c.ID = i.CIVILITE_ID)
            JOIN STRUCTURE s ON (s.ID = i.STRUCTURE_ID)
        WHERE
            (i.source_code = :criterionId OR ($sqlCrit))
            AND rownum <= :limit
        ORDER BY
            nom_usuel, prenom
EOS;

        $res = $this->query($sql, $params );

        $result = array();
        $f = new \Common\Filter\IntervenantTrouveFormatter();
        foreach( $res as $r ){
//            $dateNaissance = new DateTime( $r['DATE_NAISSANCE'] );
//            $result[$r['SOURCE_CODE']] = array(
//               'id'    => $r['SOURCE_CODE'],
//               'label' => $r['NOM_USUEL'].' '.$r['PRENOM'],
//               'extra' => '(n° '.$r['SOURCE_CODE'].', né'.('M.' == $r['CIVILITE'] ? '' : 'e').' le '.$dateNaissance->format('d/m/Y').')',
//            );
            $result[$r['SOURCE_CODE']] = $f->filter($r);
        }

        return $result;
    }

}