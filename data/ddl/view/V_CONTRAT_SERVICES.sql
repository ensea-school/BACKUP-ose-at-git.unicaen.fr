CREATE OR REPLACE FORCE VIEW V_CONTRAT_SERVICES AS
WITH services AS (

        SELECT
            s.intervenant_id                                                            intervenant_id,
            c.id                                                                        contrat_id,
            str.id                                                                      structure_id,
            str.libelle_court                                                           "serviceComposante",
            ep.code                                                                     "serviceCode",
            ep.libelle                                                                  "serviceLibelle",
            ep.id                                                                       element_pedagogique_id,
            NULL                                                                        fonction_referentiel_id,
            NULL                                                                        type_mission_id,
            NULL                                                                        mission_id,
            SUM(CASE WHEN ti.code = 'CM' THEN vh.heures ELSE 0 END)                     heures_cm,
            SUM(CASE WHEN ti.code = 'TD' THEN vh.heures ELSE 0 END)                     heures_td,
            SUM(CASE WHEN ti.code = 'TP' THEN vh.heures ELSE 0 END)                     heures_tp,
            SUM(CASE WHEN ti.code NOT IN ('CM','TD','TP') THEN vh.heures ELSE 0 END)    heures_autres,
            SUM(vh.heures)                                                              heures_totales,
            SUM(frvh.total)                                                             hetd,
            'ENS'                                                                       "typeServiceCode",
            p.libelle_long                                                              "periode"
        FROM
            volume_horaire vh
            JOIN service s ON s.id = vh.service_id
            JOIN type_intervention ti ON ti.id = vh.type_intervention_id
            JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
            JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id
            JOIN etat_volume_horaire evh ON evh.code = 'valide'
            JOIN formule_resultat_intervenant fr ON fr.intervenant_id = s.intervenant_id AND fr.etat_volume_horaire_id = evh.id
            JOIN formule_resultat_volume_horaire frvh ON frvh.volume_horaire_id = vh.id AND frvh.formule_resultat_intervenant_id = fr.id
            JOIN STRUCTURE str ON ep.structure_id = str.id
            LEFT JOIN contrat c ON c.id = vh.contrat_id
            LEFT JOIN periode p on vh.periode_id = p.id
        WHERE
            vh.histo_destruction IS NULL
            AND tvh.code = 'PREVU'
        GROUP BY
            s.intervenant_id,
            c.id,
            str.libelle_court,
            ep.code,
            ep.libelle,
            str.id,
            ep.id,
            p.libelle_long


    UNION ALL


        SELECT
            sr.intervenant_id       intervenant_id,
            c.id                    contrat_id,
            str.id                  structure_id,
            str.libelle_court       "serviceComposante",
            fr.code                 "serviceCode",
            fr.libelle_long         "serviceLibelle",
            NULL                    element_pedagogique_id,
            fr.id                   fonction_referentiel_id,
            NULL                    type_mission_id,
            NULL                    mission_id,
            0                       heures_cm,
            0                       heures_td,
            0                       heures_tp,
            SUM(vhr.heures)         heures_autres,
            SUM(vhr.heures)         heures_totales,
            SUM(frvh.total)         hetd,
            'REF'                  "typeServiceCode",
            NULL                   "periode"
            FROM
            volume_horaire_ref vhr
            JOIN service_referentiel sr ON sr.id = vhr.service_referentiel_id
            JOIN fonction_referentiel fr ON fr.id = sr.fonction_id
            JOIN type_volume_horaire tvh ON tvh.id = vhr.type_volume_horaire_id
            JOIN etat_volume_horaire evh ON evh.code = 'valide'
            JOIN formule_resultat_intervenant fr ON fr.intervenant_id = sr.intervenant_id AND fr.etat_volume_horaire_id = evh.id
            LEFT JOIN formule_resultat_volume_horaire frvh ON frvh.volume_horaire_ref_id = vhr.id AND frvh.formule_resultat_intervenant_id = fr.id
            LEFT JOIN contrat c ON c.id = vhr.contrat_id
            LEFT JOIN STRUCTURE str ON sr.structure_id = str.id
        WHERE
            vhr.histo_destruction IS NULL
            AND tvh.code = 'PREVU'
        GROUP BY
            sr.intervenant_id,
            c.id,
            str.libelle_court,
            fr.code,
            fr.libelle_long,
            str.id,
            fr.id

    UNION ALL


        SELECT
            m.intervenant_id        intervenant_id,
            c.id                    contrat_id,
            str.id                  structure_id,
            str.libelle_court       "serviceComposante",
            tm.code                 "serviceCode",
            tm.libelle              "serviceLibelle",
            NULL                    element_pedagogique_id,
            NULL                    fonction_referentiel_id,
            tm.id                   type_mission_id,
            m.id                    mission_id,
            0                       heures_cm,
            0                       heures_td,
            0                       heures_tp,
            SUM(vhm.heures)         heures_autres,
            SUM(vhm.heures)         heures_totales,
            SUM(vhm.heures)         hetd,
            'MIS'                   "typeServiceCode",
            NULL                    "periode"
 FROM
            volume_horaire_mission vhm
            JOIN mission m ON m.id = vhm.mission_id
            JOIN type_mission tm ON m.type_mission_id = tm.id
            LEFT JOIN contrat c ON c.id = vhm.contrat_id
            JOIN STRUCTURE str ON m.structure_id = str.id
            LEFT JOIN validation_vol_horaire_miss vvhm ON vvhm.volume_horaire_mission_id = vhm.id
            JOIN validation v ON v.id = vvhm.validation_id AND v.histo_destruction IS NULL
            JOIN type_volume_horaire tvh ON tvh.id = vhm.type_volume_horaire_id
            JOIN validation_mission vm ON vm.mission_id = m.id
            JOIN validation v2 ON v2.id = vm.validation_id AND v2.histo_destruction IS NULL
        WHERE
            vhm.histo_destruction IS NULL
            AND tvh.code = 'PREVU'
            AND (v.id IS NOT NULL OR vhm.auto_validation = 1)
            AND m.histo_destruction IS NULL
            AND (v2.id IS NOT NULL OR vhm.auto_validation = 1)
        GROUP BY
            m.intervenant_id,
            c.id,
            str.libelle_court,
            tm.code,
            tm.libelle,
            str.id,
            tm.id,
            m.id
        )

        SELECT
            rownum id,
            res.intervenant_id,
            res.contrat_id,
            res.type_service_id,
            res.structure_id,
            res."serviceCode"   service_code,
            res."serviceLibelle" service_libelle,
            res.element_pedagogique_id,
            res.fonction_referentiel_id,
            res.type_mission_id,
            res.mission_id,
            res."cm" cm,
            res."td" td,
            res."tp" tp,
            res."autres" autres,
            res.heures,
            res."serviceHeures" service_heures,
            res."serviceComposante",
            res."serviceCode",
            res."serviceLibelle",
            res."cm",
            res."td",
            res."tp",
            res."autres",
            res."serviceHeures",
            res."hetd",
            res."typeService",
            res."periode"
        FROM
        (
            SELECT
                s.intervenant_id                                                                                                                 intervenant_id,
                s.contrat_id                                                                                                                     contrat_id,
                ts.id                                                                                                                            type_service_id,
                s.structure_id                                                                                                                   structure_id,
                s."serviceComposante"                                                                                                            "serviceComposante",
                s."serviceCode"                                                                                                                  "serviceCode",
                s."serviceLibelle"                                                                                                               "serviceLibelle",
                s.element_pedagogique_id                                                                                                         element_pedagogique_id,
                s.fonction_referentiel_id                                                                                                        fonction_referentiel_id,
                s.type_mission_id                                                                                                                type_mission_id,
                s.mission_id                                                                                                                     mission_id,
                CASE WHEN SUM(s.heures_cm) = 0     THEN to_char(0) ELSE REPLACE(ltrim(to_char(SUM(s.heures_cm), '999999.00')),'.',',')     END   "cm",
                CASE WHEN SUM(s.heures_td) = 0     THEN to_char(0) ELSE REPLACE(ltrim(to_char(SUM(s.heures_td), '999999.00')),'.',',')     END   "td",
                CASE WHEN SUM(s.heures_tp) = 0     THEN to_char(0) ELSE REPLACE(ltrim(to_char(SUM(s.heures_tp), '999999.00')),'.',',')     END   "tp",
                CASE WHEN SUM(s.heures_autres) = 0 THEN to_char(0) ELSE REPLACE(ltrim(to_char(SUM(s.heures_autres), '999999.00')),'.',',') END   "autres",
                SUM(heures_totales)                                                                                                              heures,
                SUM(heures_totales)                                                                                                              "serviceHeures",
                SUM(hetd)                                                                                                                        "hetd",
                s."typeServiceCode"                                                                                                              "typeService",
                s."periode"                                                                                                                      "periode"
            FROM
                services s
                JOIN TYPE_SERVICE ts ON ts.code = s."typeServiceCode"
            GROUP BY
                s.intervenant_id,
                s.contrat_id,
                s."serviceComposante",
                s."serviceCode",
                s."serviceLibelle",
                s."typeServiceCode",
                s.structure_id,
                ts.id,
                element_pedagogique_id,
                fonction_referentiel_id,
                type_mission_id,
                mission_id,
                s."periode"
        ) res