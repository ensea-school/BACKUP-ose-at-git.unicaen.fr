CREATE OR REPLACE FORCE VIEW V_CONTRAT_SERVICES AS
WITH services AS (
    SELECT
        s.intervenant_id                                                            intervenant_id,
        ts.code                                                                     code,
        c.id                                                                        contrat_id,
        str.libelle_court                                                           "serviceComposante",
        ep.code                                                                     "serviceCode",
        ep.libelle                                                                  "serviceLibelle",
        SUM(CASE WHEN ti.code = 'CM' THEN vh.heures ELSE 0 END)                     heures_cm,
        SUM(CASE WHEN ti.code = 'TD' THEN vh.heures ELSE 0 END)                     heures_td,
        SUM(CASE WHEN ti.code = 'TP' THEN vh.heures ELSE 0 END)                     heures_tp,
        SUM(CASE WHEN ti.code NOT IN ('CM','TD','TP') THEN vh.heures ELSE 0 END)    heures_autres,
        SUM(vh.heures)                                                              heures_totales
    FROM
        volume_horaire vh
        JOIN service s ON s.id = vh.service_id
        JOIN type_intervention ti ON ti.id = vh.type_intervention_id
        JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
        JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id
        LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
        JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
        LEFT JOIN contrat c ON c.id = vh.contrat_id
        LEFT JOIN structure str ON c.structure_id = str.id
        JOIN type_service ts ON ts.code = 'ENS'
    WHERE
        vh.histo_destruction IS NULL
        AND tvh.code = 'PREVU'
        AND (v.id IS NOT NULL OR vh.auto_validation = 1)
    GROUP BY
        s.intervenant_id,
        c.id,
        str.libelle_court,
        ep.code,
        ep.libelle,
        ts.code
    UNION ALL
    SELECT
        sr.intervenant_id       intervenant_id,
        ts.code                 code,
        c.id                    contrat_id,
        str.libelle_court       "serviceComposante",
        fr.code                 "serviceCode",
        fr.libelle_long         "serviceLibelle",
        0                       heures_cm,
        0                       heures_td,
        0                       heures_tp,
        SUM(vhr.heures)         heures_autres,
        SUM(vhr.heures)         heures_totales
    FROM
        volume_horaire_ref vhr
        JOIN service_referentiel sr ON sr.id = vhr.service_referentiel_id
        JOIN fonction_referentiel fr ON fr.id = sr.fonction_id
        JOIN type_volume_horaire tvh ON tvh.id = vhr.type_volume_horaire_id
        LEFT JOIN validation_vol_horaire_ref vvhr ON vvhr.volume_horaire_ref_id = vhr.id
        JOIN validation v ON v.id = vvhr.validation_id AND v.histo_destruction IS NULL
        LEFT JOIN contrat c ON c.id = vhr.contrat_id
        LEFT JOIN structure str ON c.structure_id = str.id
        JOIN type_service ts ON ts.code = 'REF'
    WHERE
        vhr.histo_destruction IS NULL
        AND tvh.code = 'PREVU'
        AND (v.id IS NOT NULL OR vhr.auto_validation = 1)
    GROUP BY
        sr.intervenant_id,
        c.id,
        str.libelle_court,
        ts.code,
        fr.code,
        fr.libelle_long
    UNION ALL
        SELECT
            m.intervenant_id        intervenant_id,
            ts.code                 code,
            c.id                    contrat_id,
            str.libelle_court       "serviceComposante",
            tm.code                 "serviceCode",
            tm.libelle              "serviceLibelle",
            0                       heures_cm,
            0                       heures_td,
            0                       heures_tp,
            SUM(vhm.heures)         heures_autres,
            SUM(vhm.heures)         heures_totales
        FROM
            volume_horaire_mission vhm
            JOIN mission m ON m.id = vhm.mission_id
            JOIN type_mission tm ON m.type_mission_id = tm.id
            LEFT JOIN contrat c ON c.id = vhm.contrat_id
            LEFT JOIN structure str ON c.structure_id = str.id
            LEFT JOIN validation_vol_horaire_miss vvhm ON vvhm.volume_horaire_mission_id = vhm.id
            JOIN validation v ON v.id = vvhm.validation_id AND v.histo_destruction IS NULL
            JOIN type_volume_horaire tvh ON tvh.id = vhm.type_volume_horaire_id
            JOIN type_service ts ON ts.code = 'MIS'
        WHERE
            vhm.histo_destruction IS NULL
            AND tvh.code = 'PREVU'
            AND (v.id IS NOT NULL OR vhm.auto_validation = 1)
        GROUP BY
            m.intervenant_id,
            c.id,
            str.libelle_court,
            ts.code,
            tm.code,
            tm.libelle
        )
    SELECT
        s.intervenant_id,
        s.contrat_id,
        s."serviceComposante",
        s."serviceCode",
        s."serviceLibelle",
        CASE WHEN SUM(s.heures_cm) = 0     THEN to_char(0) ELSE REPLACE(ltrim(to_char(SUM(s.heures_cm), '999999.00')),'.',',')     END   "cm",
        CASE WHEN SUM(s.heures_td) = 0     THEN to_char(0) ELSE REPLACE(ltrim(to_char(SUM(s.heures_td), '999999.00')),'.',',')     END   "td",
        CASE WHEN SUM(s.heures_tp) = 0     THEN to_char(0) ELSE REPLACE(ltrim(to_char(SUM(s.heures_tp), '999999.00')),'.',',')     END   "tp",
        CASE WHEN SUM(s.heures_autres) = 0 THEN to_char(0) ELSE REPLACE(ltrim(to_char(SUM(s.heures_autres), '999999.00')),'.',',') END   "autres",
        SUM(heures_totales)                                                                                                               heures,
        SUM(heures_totales)                                                                                                              "serviceHeures",
        s.code                                                                                                                           "typeService"
    FROM
        services s
    GROUP BY
        s.intervenant_id,
        s.contrat_id,
        s."serviceComposante",
        s."serviceCode",
        s."serviceLibelle",
        s.code