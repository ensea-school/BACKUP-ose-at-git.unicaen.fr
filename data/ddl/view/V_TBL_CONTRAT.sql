CREATE OR REPLACE FORCE VIEW V_TBL_CONTRAT AS
WITH services AS (

        SELECT
            s.intervenant_id                                                            intervenant_id,
            c.id                                                                        contrat_id,
            str.id                                                                      structure_id,
            str.libelle_court                                                           service_composante,
            ep.code                                                                     service_code,
            ep.libelle                                                                  service_libelle,
            ep.id                                                                       element_pedagogique_id,
            NULL                                                                        fonction_referentiel_id,
            NULL                                                                        type_mission_id,
            NULL                                                                        mission_id,
            SUM(CASE WHEN ti.code = 'CM' THEN vh.heures ELSE 0 END)                     heures_cm,
            SUM(CASE WHEN ti.code = 'TD' THEN vh.heures ELSE 0 END)                     heures_td,
            SUM(CASE WHEN ti.code = 'TP' THEN vh.heures ELSE 0 END)                     heures_tp,
            SUM(CASE WHEN ti.code NOT IN ('CM','TD','TP') THEN vh.heures ELSE 0 END)    heures_autres,
            SUM(vh.heures)                                                              heures_totales,
            'ENS'                                                                       type_service_code
        FROM
            volume_horaire vh
            JOIN service s ON s.id = vh.service_id
            JOIN type_intervention ti ON ti.id = vh.type_intervention_id
            JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
            JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id
            LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
            JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
            JOIN STRUCTURE str ON ep.structure_id = str.id
            LEFT JOIN contrat c ON c.id = vh.contrat_id
        WHERE
            vh.histo_destruction IS NULL
            AND tvh.code = 'PREVU'
            AND (v.id IS NOT NULL OR vh.auto_validation = 1)
            /*@INTERVENANT_ID=s.intervenant_id*/
            GROUP BY
            s.intervenant_id,
            c.id,
            str.libelle_court,
            ep.code,
            ep.libelle,
            str.id,
            ep.id


    UNION ALL


        SELECT
            sr.intervenant_id       intervenant_id,
            c.id                    contrat_id,
            str.id                  structure_id,
            str.libelle_court       service_composante,
            fr.code                 service_code,
            fr.libelle_long         service_libelle,
            NULL                    element_pedagogique_id,
            fr.id                   fonction_referentiel_id,
            NULL                    type_mission_id,
            NULL                    mission_id,
            0                       heures_cm,
            0                       heures_td,
            0                       heures_tp,
            SUM(vhr.heures)         heures_autres,
            SUM(vhr.heures)         heures_totales,
            'REF'                   type_service_code
        FROM
            volume_horaire_ref vhr
            JOIN service_referentiel sr ON sr.id = vhr.service_referentiel_id
            JOIN fonction_referentiel fr ON fr.id = sr.fonction_id
            JOIN type_volume_horaire tvh ON tvh.id = vhr.type_volume_horaire_id
            LEFT JOIN validation_vol_horaire_ref vvhr ON vvhr.volume_horaire_ref_id = vhr.id
            JOIN validation v ON v.id = vvhr.validation_id AND v.histo_destruction IS NULL
            LEFT JOIN contrat c ON c.id = vhr.contrat_id
            LEFT JOIN STRUCTURE str ON sr.structure_id = str.id
        WHERE
            vhr.histo_destruction IS NULL
            AND tvh.code = 'PREVU'
            AND (v.id IS NOT NULL OR vhr.auto_validation = 1)
            /*@INTERVENANT_ID=sr.intervenant_id*/
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
            str.libelle_court       service_composante,
            tm.code                 service_code,
            tm.libelle              service_libelle,
            NULL                    element_pedagogique_id,
            NULL                    fonction_referentiel_id,
            tm.id                   type_mission_id,
            m.id                    mission_id,
            0                       heures_cm,
            0                       heures_td,
            0                       heures_tp,
            SUM(vhm.heures)         heures_autres,
            SUM(vhm.heures)         heures_totales,
            'MIS'                   type_service_code
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
            /*@INTERVENANT_ID=m.intervenant_id*/
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
            res.structure_id,
            res.service_code,
            res.service_libelle,
            res.heures_cm,
            res.heures_td,
            res.heures_tp,
            res.autres,
            res.heures,
            res.service_composante,
            res.type_service_code,
            COALESCE(trm.id, trs.id,tr.id)      taux_remu,
            tmm.id                              taux_majore
            FROM
        (
            SELECT
                s.intervenant_id,
                s.contrat_id,
                ts.id                   type_service_id,
                s.structure_id,
                s.service_composante,
                s.service_code,
                s.service_libelle,
                s.element_pedagogique_id,
                s.fonction_referentiel_id,
                s.type_mission_id,
                s.mission_id,
                s.heures_cm,
                s.heures_td,
                s.heures_tp,
                s.heures_autres autres,
                SUM(heures_totales) heures,
                s.type_service_code
            FROM
                services s
                JOIN TYPE_SERVICE ts ON ts.code = s.type_service_code
            GROUP BY
                s.intervenant_id,
                s.contrat_id,
                ts.id,
                s.structure_id,
                s.service_composante,
                s.service_code,
                s.service_libelle,
                s.element_pedagogique_id,
                s.fonction_referentiel_id,
                s.type_mission_id,
                s.mission_id,
                s.heures_cm,
                s.heures_td,
                s.heures_tp,
                s.heures_autres,
                s.type_service_code
        ) res
        JOIN INTERVENANT i on i.id = res.intervenant_id
        JOIN statut                     si ON si.id = i.statut_id
        JOIN parametre                  p ON p.nom = 'taux-remu'
        LEFT JOIN taux_remu             trs ON si.taux_remu_id = trs.id
        LEFT JOIN mission               m ON res.mission_id = m.id
        LEFT JOIN taux_remu             trm ON m.taux_remu_id = trm.id
        LEFT JOIN taux_remu             tmm ON tmm.id = m.taux_remu_majore_id
        LEFT JOIN taux_remu             tr ON tr.id = p.valeur